<?php

namespace App\Http\Controllers;

use App\Models\Correction;
use Illuminate\Http\Request;

class CorrectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $corrections = Correction::all();
            return datatables($corrections)->toJson();
        }

        return view('correction.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('correction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
		$stocknumbers = $request->get("stocknumber");
		$quantity = $request->get("quantity");
		$unitcost = $request->get("unitcost");
		$array = [];
		$status = null;
		$details = $request->get('details');
		$created_by = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname ;

		DB::beginTransaction();

		$adjustment = Correction::create([
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('correction.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approval($id)
    {
        return view('correction.approval');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancellation($id)
    {
        return view('correction.cancel');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, $id)
    {
        //
    }
}
