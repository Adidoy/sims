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
    public function index(Request $request) {
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

        // $requests = App\Request::orderBy('created_at','desc')->first();
        // $id = 1;
        // $now = Carbon\Carbon::now();
        // $const = $now->format('y') . '-' . $now->format('m');
    
        // if(count($requests) > 0) {
        //   $id = $requests->id + 1;
        // }
        // else {
        //   $id = count(App\StockCard::filterByIssued()->get()) + 1;
        // }
    
        // if($request->ajax()) {
        //   return json_encode( $const . '-' . $id ); 
        // }
    
        // if (strlen($id) == 1) 
        //   $requestcode =  '000'.$id;
        // elseif (strlen($id) == 2) 
        //   $requestcode =  '00'.$id;
        // elseif (strlen($id) == 3) 
        //   $requestcode =  '0'.$id;
        // elseif (strlen($id) == 4) 
        //   $requestcode =  $id;
        // else
        //   $requestcode =  $id;
        
        return $const . '-' . $requestCode;
    
    }
}