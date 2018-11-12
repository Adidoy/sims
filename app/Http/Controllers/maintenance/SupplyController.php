<?php

namespace App\Http\Controllers\Maintenance;

use App\Unit;
use App\Supply;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Commands\Supply\AddSupply;
use App\Http\Controllers\Controller;
use App\Commands\Supply\UpdateSupply;
use App\Http\Requests\SupplyRequest\SupplyStoreRequest;
use App\Http\Requests\SupplyRequest\SupplyUpdateRequest;

class SupplyController extends Controller 
{

	protected $printOrientation = 'Portrait';
	protected $printBladeTemplate = "maintenance.supply.print_index";

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			$supplies = Supply::with('unit')->get();
			return datatables($supplies)->toJson();
		}

		return view('maintenance.supply.index')
                ->with('title', 'Supply Maintenance');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$units = Unit::pluck('name','id');

		return view('maintenance.supply.create')
				->with('unit', $units)
                ->with('title', 'Supply Maintenance');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SupplyStoreRequest $request)
	{
		$this->dispatch(new AddSupply($request));
		\Alert::success( __('supplies.add_to_inventory') )->flash();
		return redirect('maintenance/supply');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{

		if($request->ajax()) {
			$supply = Supply::find($id);
			return datatables($supply)->toJson();
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$supply = Supply::findOrFail($id);
		$units = Unit::pluck('name', 'id');

		return view('maintenance.supply.edit')
				->with('supply',$supply)
				->with('unit', $units)
        		->with('title','Supply Maintenance');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,  $id)
	{
		$this->dispatch(new UpdateSupply($request));
		\Alert::success( __('supplies.successful_update') )->flash();
		return redirect('maintenance/supply');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$supply = Supply::findOrFail($id)->delete();

		\Alert::success( __('supplies.successful_remove') )->flash();
		return redirect('maintenance/supply');
	}

	public function print()
	{
		$filename = "StockMasterlist-" . Carbon::now()->format('mdYHm') . ".pdf";
		$data = [
			'supplies' => Supply::all()
		];

		return $this->printPreview($this->printBladeTemplate, $data, $filename, $this->printOrientation);
	}


}
