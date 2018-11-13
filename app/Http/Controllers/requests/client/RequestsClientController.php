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
        $requests = RequestClient::findOfficeRequest(Auth::user()->office)->get();
        if($request->ajax()) {
          return datatables($requests)->toJson();
        }
        return view('requests.client.forms.index')
          ->with('title','Request');
    }

    public function create(Request $request) 
    {
        $code = $this->generate($request);
        return view('requests.client.forms.create')
          ->with('code',$code)
          ->with('title','Request');
    }

    public function generate(Request $request) 
    {
        $now = Carbon\Carbon::now();
        $const = $now->format('y') . '-' . $now->format('m');
        $requests = App\Request::orderBy('created_at','desc')->first();
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
        $code = $this->generate($request);
        $stocknumbers = $request->get("stocknumber");
        $quantity = $request->get("quantity");
        $quantity_issued = null;
        $array = [];
        $office = App\Office::findByCode(Auth::user()->office)->id;
        $status = null;
        $purpose = $request->get("purpose");
        $requestor = Auth::user()->id;

        if(count($stocknumbers) <= 0 ) return back()->withInput()->withErrors(['Invalid Stock List Requested']);

        foreach(array_flatten($stocknumbers) as $stocknumber) {
            if($stocknumber == '' || $stocknumber == null || !isset($stocknumber)) {
                \Alert::error('Encountered an invalid stock! Resetting table')->flash();
                return redirect("request/create");
            }   

            $validator = Validator::make([
                'Purpose' => $purpose,
                'Stock Number' => $stocknumber,
                'Quantity' => $quantity["$stocknumber"]
            ],App\Request::$issueRules);

            if($validator->fails()) {
                return redirect("request/create")
                    ->with('total',count($stocknumbers))
                    ->with('stocknumber',$stocknumbers)
                    ->with('quantity',$quantity)
                    ->withInput()
                    ->withErrors($validator);
            }

            array_push($array,[
                'quantity_requested' => $quantity["$stocknumber"],
                'supply_id' => App\Supply::findByStockNumber($stocknumber)->id,
                'quantity_issued' => $quantity_issued
            ]);
        }
        DB::beginTransaction();

        $request = App\Request::create([
            'local' => $code,
            'requestor_id' => $requestor,
            'issued_by' => null,
            'office_id' => $office,
            'remarks' => null,
            'purpose' => $purpose,
            'status' => $status
        ]);

        $request->supplies()->sync($array);

        $office = App\Office::find($office);
        $requestor = App\User::find($requestor);
        DB::commit();
        \Alert::success('Request Sent')->flash();
        return redirect('request');
    }
}