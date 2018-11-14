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

class RequestsClientController extends Controller
{
    public function index(Request $request) 
    {
        if ($request->url() == url('request/pending')) {
            $requests = RequestClient::findOfficeRequest(Auth::user()->office)->whereNull('status')->get();
        } else if ($request->url() == url('request/approved')) {
            $requests = RequestClient::findOfficeRequest(Auth::user()->office)->where('status','=','approved')->get();
        }  else if ($request->url() == url('request/released')) {
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
        $requests = App\RequestClient::orderBy('created_at','desc')->first();
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
        $newRequest = new RequestClient;

        $quantity_issued = null;
        
        $validator = Validator::make([
            'Purpose' => $purpose
        ], $newRequest->rules(), $newRequest->messages());
        
        if($validator->fails()) {
            return redirect("request/create")
                ->withInput()
                ->withErrors($validator);
        }
        
    }

    public function show(Request $request,$id) 
    {
        $requests = App\Request::find($id);
        if($request->ajax()) {
          $supplies = $requests->supplies;
          return json_encode([
            'data' => $supplies
          ]);
        }
        return view('request.show')
          ->with('request',$requests)
          ->with('title','Request');
    }
}