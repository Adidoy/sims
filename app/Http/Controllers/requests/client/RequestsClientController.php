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
        if ($request->url() == url('request/client/pending')) {
            $requests = RequestClient::findOfficeRequest(Auth::user()->office)->whereNull('status')->get();
        } else if ($request->url() == url('request/client/approved')) {
            $requests = RequestClient::findOfficeRequest(Auth::user()->office)->where('status','=','approved')->get();
        }  else if ($request->url() == url('request/client/released')) {
            $requests = RequestClient::findOfficeRequest(Auth::user()->office)->where('status','=','released')->get();
        }
        if($request->ajax()) {
          return datatables($requests)->toJson();
        }
        return view('requests.client.forms.index')
          ->with('title','Request');
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
}