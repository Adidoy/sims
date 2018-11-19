<?php

namespace App\Http\Controllers\Requests\Client;


use DB;
use App;
use PDF;
use Auth;
use Event;
use Carbon;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Requests\Client\RequestClient;
use App\Models\Requests\Client\RequestDetailsClient;

class RequestsClientController extends Controller
{
    public function index(Request $request) 
    {
        if(isset($request->type)) {
            if($request->type == 'pending') {
                $requests = RequestClient::findOfficeRequest(Auth::user()->office)->whereNull('status')->get();    
            } elseif ($request->type == 'approved') {
                $requests = RequestClient::findOfficeRequest(Auth::user()->office)->where('status','=','approved')->get();
            } elseif ($request->type == 'released') {
                $requests = RequestClient::findOfficeRequest(Auth::user()->office)->where('status','=','released')->get();
            } elseif ($request->type == 'disapproved') {
                $requests = RequestClient::findOfficeRequest(Auth::user()->office)
                    ->where(function($query) {
                        $query->where('status','=','cancelled')
                                ->orWhere('status','=','disapproved');
                    })
                    ->get();
            }
        }
        $type = $request->type;
        if($request->ajax()) {
          return datatables($requests)->toJson();
        }
        return view('requests.client.forms.index', compact('type', 'requests'));
    }

    public function create(Request $request) 
    {
        return view('requests.client.forms.create')
          ->with('title','Request');
    }

    public function generate(Request $request) 
    {
        $now = Carbon\Carbon::now();
        $const = $now->format('y') . '-' . $now->format('m');
        $requests = RequestClient::orderBy('created_at','desc')->first();
		$id = $requests->id + 1;
	
		if (strlen($id) == 1) 
		  $requestCode =  '000'.$id;
		elseif (strlen($id) == 2) 
		  $requestCode =  '00'.$id;
		elseif (strlen($id) == 3) 
		  $requestCode =  '0'.$id;
		elseif (strlen($id) == 4) 
		  $requestCode =  $id;
		else
          $requestCode =  $id;
          
        return $const . '-' . $requestCode;
    }

    public function store(Request $request)
    {
        $stocknumbers = $request->get("stocknumber");
        $quantity = $request->get("quantity");
        $purpose = $request->get("purpose");
        $requestor = Auth::user()->id;
        $office = App\Office::findByCode(Auth::user()->office)->id;
        $newRequest = new RequestClient;
        $newRequestDetails = new RequestDetailsClient;        
        $code = $this->generate($request);

        $validator = Validator::make([
            'Purpose' => $request->get("purpose")
        ], $newRequest->requestRules(), $newRequest->requestMessages());

        
        if($validator->fails()) {
            return redirect("request/client/create")
                ->withInput()
                ->withErrors($validator);
        }

        foreach($stocknumbers as $stocknumber) {
            $validator = Validator::make([
                'Stock Number' => $stocknumber,
                'Quantity' => $quantity["$stocknumber"]
            ],$newRequestDetails->requestDetailsRules(), $newRequestDetails->requestDetailsMessages());
            
            if($validator->fails()) {
                return redirect("request/client/create")
                    ->withInput()
                    ->withErrors($validator);
            }
        }

        if(count($stocknumbers) <= 0 ) 
            return back()
                ->withInput()
                ->withErrors(['Invalid Stock List Requested']);

        try{
            DB::beginTransaction();
            $newRequest = RequestClient::create([
                'local' => $code,
                'requestor_id' => $requestor,
                'issued_by' => null,
                'office_id' => $office,
                'remarks' => null,
                'purpose' => $purpose
            ]);
            foreach($stocknumbers as $stocknumber) {
                $newRequestDetails = RequestDetailsClient::create([
                    'supply_id' => App\Supply::stockNumber($stocknumber)->first()->id, 
                    'request_id' => $newRequest->id, 
                    'quantity_requested' => $quantity["$stocknumber"]
                ]);
            }
            DB::commit();
            \Alert::success('Request Sent')->flash();
            return redirect('request/pending');
        } catch(\Exception $e) {
          DB::rollback();
          \Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
          return redirect('request/client/create'); 
        }
    }

    public function show(Request $request,$id) 
    {
        $requests = RequestClient::find($id);
        if($request->ajax()) {
          $supplies = $requests->supplies;
          return json_encode([
            'data' => $supplies
          ]);
        }
        return view('requests.client.forms.show')
          ->with('request',$requests)
          ->with('title','Request');
    }

    public function getCancelRequest($id)
    {
        $request = App\Request::find($id);
        return view('requests.client.forms.cancel')
            ->with('request',$request)
            ->with('title',$request->id);
    }

    public function cancelRequest(Request $request, $id)
    {
        $updateRequest = RequestClient::find($id);
        try{
            DB::beginTransaction();
            $validator = Validator::make([
                'Remarks' => $request->get("details"),
            ],$updateRequest->requestRules(),$updateRequest->requestMessages());
            
            if($validator->fails()) {
                return redirect("request/client/$id/cancel")
                  ->withInput()
                  ->withErrors($validator);
            }
            $updateRequest->status = "cancelled";
            $updateRequest->cancelled_by = Auth::user()->id;
            $updateRequest->cancelled_at = Carbon\Carbon::now();
            $updateRequest->remarks = $details;
            $updateRequest->save();
            DB::commit();
            \Alert::success('Request cancelled')->flash();
            return redirect('/');
        } catch(\Exception $e) {
          DB::rollback();
          \Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
          return redirect('/'); 
        }
    }

    public function printRIS($id) {
        $orientation = 'Portrait';
        $id = $this->sanitizeString($id);
        $request = RequestClient::find($id);
        $signatory = '';
    
        $row_count = 16;
        $adjustment = 0;
        if(isset($request->supplies)):
          $data_count = count($request->supplies) % $row_count;
          if($data_count == 0 || (($data_count < 5) && (count($request->supplies) > $row_count))):
            if((count($request->supplies) > $row_count) && ($data_count < 7)):
              $remaining_rows = $data_count + $row_count + $adjustment;
            else:
              $remaining_rows = 0;
            endif;
          else:
            $remaining_rows = $row_count - $data_count;
          endif;
        endif;
    
        $user = App\User::where('id','=',$request->requestor_id)->first();
        $office = App\Office::where('code','=',$user->office)->first();
        $sector = App\Office::where('id','=',$office->head_office)->first();
        $issuedby = App\User::where('id','=',$request->issued_by)->first();
        
        //checks if the sector has a head_office
        //for lvl 2 offices
        if(isset($sector->head_office)): 
          $office = App\Office::where('id','=',$office->head_office)->first(); 
          $sector = App\Office::where('id','=',$sector->head_office)->first(); 
        elseif($office->head_office == NULL): 
            if(App\Office::where('code','like',$office->code.'-A'.$office->code)->first() !== NULL):
              $office = App\Office::where('code','like',$office->code.'-A'.$office->code)->first();
            endif;
        endif; 
        
        //checks if the sector has a head_office
        //for lvl 3 offices
        if(isset($sector->head_office)):
          $office = App\Office::where('id','=',$office->head_office)->first();
          $sector = App\Office::where('id','=',$sector->head_office)->first();
        endif;
        
        //checks if the sector has a head_office
        //for lvl 4 offices
        if(isset($sector->head_office)):
            $office = App\Office::where('id','=',$office->head_office)->first();
            $sector = App\Office::where('id','=',$sector->head_office)->first();
        endif;
    
        if($request->status == 'Released' || $request->status == 'released'):
          $signatory = App\RequestSignatory::where('request_id','=',$request->id)->get();
        endif;
        $data = [
          'request' => $request, 
          'office' => $office,
          'sector' => $sector,
          'signatory' => $signatory,
          'issuedby' => $issuedby,
          'row_count' => $row_count,
          'pages' => $data_count,
          'end' => $remaining_rows
        ];
    
        $filename = "Request-".Carbon\Carbon::now()->format('mdYHm')."-"."$request->code".".pdf";
        $view = "requests.client.reports.print_ris";
        return $this->printPreview($view,$data,$filename,$orientation);
    }
}