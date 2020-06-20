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
use App\Models\Requests\Signatory\RequestSignatory;
use App\Models\Requests\Client\RequestDetailsClient;

class RequestsClientController extends Controller
{

    public function pendingRequests(Request $request)
    {
        $requests = RequestClient::findOfficeRequest(Auth::user()->office)
            ->whereNull('status')
            ->orderBy('created_at', 'desc')
            ->get();   
        $type = "pending";
        if($request->ajax()) {
            return datatables($requests)->toJson();
        }
        return view('requests.client.forms.index', compact('type', 'requests'));
    }

    public function approvedRequests(Request $request)
    {
        $requests = RequestClient::findOfficeRequest(Auth::user()->office)
            ->where('status','=','approved')
            ->orderBy('approved_at', 'desc')
            ->get();
        $type = "approved";
        if($request->ajax()) {
            return datatables($requests)->toJson();
        }
        return view('requests.client.forms.index', compact('type', 'requests'));
    }

    public function releasedRequests(Request $request)
    {
        $requests = RequestClient::findOfficeRequest(Auth::user()->office)
            ->where('status','=','released')
            ->orderBy('released_at', 'desc')
            ->get();
        $type = "released";
        if($request->ajax()) {
            return datatables($requests)->toJson();
        }
        return view('requests.client.forms.index', compact('type', 'requests'));
    }

    public function disapprovedRequests(Request $request)
    {
        $requests = RequestClient::findOfficeRequest(Auth::user()->office)
        ->where(function($query) {
            $query->where('status','=','cancelled')
                    ->orWhere('status','=','disapproved')
                    ->orWhere('status','=','request expired');
        })
        ->orderBy('cancelled_at', 'desc')
        ->get();
        $type = "disapproved";
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

    public function store(Request $request)
    {
        $stocknumbers = $request->get("stocknumber");
        $quantity = $request->get("quantity");
        $purpose = $request->get("purpose");
        $requestor = Auth::user()->id;
        $office = App\Office::findByCode(Auth::user()->office)->id;
        $newRequest = new RequestClient;
        $newRequestDetails = new RequestDetailsClient;        

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
                'local' => null,
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
            return redirect('request/client/pending');
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
        $request = RequestClient::find($id);
        return view('requests.client.forms.cancel')
            ->with('request',$request)
            ->with('title',$request->id);
    }

    public function cancelRequest(Request $request, $id)
    {
        $details = $request->get("details");
        $updateRequest = RequestClient::find($id);
        try{
            DB::beginTransaction();
            $validator = Validator::make([
                'Remarks' => $details
            ],$updateRequest->updateRules(),$updateRequest->updateMessages());
            
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
            return redirect('request/client/disapproved');
        } catch(\Exception $e) {
          DB::rollback();
          \Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
          return redirect('/'); 
        }
    }

    public function printRIS($id) 
    {
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

        $office = $request->office;
        $signatory = RequestSignatory::where('request_id', '=', $id)->first();
        
        if(isset($signatory)) {
            $headOffice = new App\Office;
            $officeSignatory = new App\Office;
        } else {
            $officeSignatory = App\Office::where('id','=',$office->id)->first();
            if(!isset($officeSignatory->head_office)) {
                $headOffice = $officeSignatory;
                $officeSignatory = App\Office::where('code','=',$officeSignatory->code.'-A'.$officeSignatory->code)->first();
            } else {
                $headOffice = App\Office::where('id','=',$officeSignatory->head_office)->first();
                while(isset($headOffice->head_office)) {
                    $officeSignatory = $headOffice;
                    $headOffice = App\Office::where('id','=',$headOffice->head_office)->first();
                }
            }
        }
        
        $data = [
          'request' => $request, 
          'office' => $office,
          'officeSignatory' => $officeSignatory,
          'headOffice' => $headOffice,
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