<?php

namespace App\Http\Controllers\Adjustments;

use DB;
use App;
use Auth;
use Carbon;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AdjustmentsController extends Controller
{
    public function returnView()
    {
        return view('adjustment.create')
					->with('title','Adjustment');
    }

    public function store(Request $request)
	{
		$stocknumbers = $request->get("stocknumber");
		$quantity = $request->get("quantity");
		$unitcost = $request->get("unitcost");
		$array = [];
		$status = null;
		$details = $request->get('details');
		$created_by = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname ;

		DB::beginTransaction();

		$adjustment = App\Adjustment::create([
			'created_by' => $created_by,
			'details' => $details,
			'status' => $status
		]);

		foreach(array_flatten($stocknumbers) as $stocknumber)
		{
			if($stocknumber == '' || $stocknumber == null || !isset($stocknumber))
			{
			  \Alert::error('Encountered an invalid stock! Resetting table')->flash();
			   return redirect("adjustment/create");
			}

			$validator = Validator::make([
			    'Stock Number' => $stocknumber,
			    'Quantity' => $quantity["$stocknumber"]
			],App\Adjustment::$rules);

			if($validator->fails())
			{
			    return redirect("adjustment/create")
			            ->with('total',count($stocknumbers))
			            ->with('stocknumber',$stocknumbers)
			            ->with('quantity',$quantity)
			            ->with('unitcost',$unitcost)
			            ->withInput()
			            ->withErrors($validator);
			}

			$supply = App\Supply::findByStockNumber($stocknumber);

			array_push($array,[
			    'quantity' => $quantity["$stocknumber"],
			    'supply_id' => $supply->id,
			    'unitcost' => $unitcost["$stocknumber"]
			]);

			$transaction = new App\StockCard;
			$transaction->date = Carbon\Carbon::now();
			$transaction->stocknumber = $supply->stocknumber;
			$transaction->reference = "Adjustment#$adjustment->code";
			$transaction->receipt = null;
			$transaction->organization = null;
			$transaction->received_quantity = $quantity["$stocknumber"];
			$transaction->daystoconsume = null;
			$transaction->user_id = Auth::user()->id;
			$transaction->receipt();
		}

		$adjustment->supplies()->sync($array);

		DB::commit();

		\Alert::success('Adjustment Report Created')->flash();
		return redirect('adjustment');
	}
}