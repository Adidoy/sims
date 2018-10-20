<?php
namespace App\Http\Controllers;

use App;
use Validator;
use Carbon;
use DB;
use Auth;
use PDF;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DeliveryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $id) {
		$stockcard = App\StockCard::findBySupplyId($id)
			->orderBy('reference', 'asc')
			->orderBy('created_at','desc')
			->get();
        if($request->ajax()) {
			return datatables($stockcard)->toJson();
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$supplier = App\Supplier::pluck('name','name');
		return view('delivery.supplies.accept')
			->with('title','Item Delivery')
			->with('type', 'stock')
			->with('supplier',$supplier);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)	{
		$po_no = $this->sanitizeString($request->get('po_no'));
		$po_date = $this->sanitizeString($request->get('po_date'));
		$invoice_no = $this->sanitizeString($request->get('invoice_no'));
		$invoice_date = $this->sanitizeString($request->get('invoice_date'));
		$dr_no = $this->sanitizeString($request->get('dr_no'));
		$dr_date = $this->sanitizeString($request->get('dr_date'));
		$supplier = $this->sanitizeString($request->get("supplier"));
		$username = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
		$stocknumbers = $request->get("stocknumber");
		$quantity = $request->get("quantity");
		$unitcost = $request->get("unitcost");

		//return $stocknumbers;

		DB::beginTransaction();
			$supplier = App\Supplier::findBySupplierName($supplier)->first();
			$delivery_header = new App\DeliveryHeader;
			$delivery_header->supplier_id = $supplier->id;
    		$delivery_header->purchaseorder_no = $po_no;
    		$delivery_header->purchaseorder_date = Carbon\Carbon::parse($po_date);
    		$delivery_header->invoice_no = $invoice_no;
    		$delivery_header->invoice_date = Carbon\Carbon::parse($invoice_date);
    		$delivery_header->delrcpt_no = $dr_no;
        	$delivery_header->delivery_date = Carbon\Carbon::parse($dr_date);
        	$delivery_header->received_by = $username;
			$delivery_header->save();

			
 			$new_delivery = App\DeliveryHeader::orderBy('created_at', 'desc') -> first();

 				//validatation from form
				foreach($stocknumbers as $stocknumber) {
					//$array = [];
					/**
			 		* save the record in the database
			 		*/

					$deliveries_detail = new App\DeliveriesDetail;
					$deliveries_detail->delivery_id = $new_delivery->id;
					$deliveries_detail->stocknumber = $stocknumber;
					$deliveries_detail->quantity_delivered = $quantity["$stocknumber"];
					$deliveries_detail->unit_cost = $unitcost["$stocknumber"];
					$deliveries_detail->save();

					// $array[$supply->id] = [
					// 	'quantity_received' => $quantity["$stocknumber"],
					// 	'daystoconsume' => $daystoconsume["$stocknumber"]
					// ];
				}
		DB::commit();
		\Alert::success('Supplies Delivery Recorded')->flash();
		return redirect('inventory/supply');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$id = $this->sanitizeString($id);
		if(Request::ajax())
		{
			$transaction = App\StockCard::with('supply')
							->findByStockNumber($id)
							->get();
			return json_encode([ 'data' => $transaction ]);
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($stocknumber,$id)
	{
		return redirect("inventory/supply/$stocknumber/stockcard/$id/edit");
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($stocknumber,$id)
	{
		\Alert::success('Supply Updated')->flash();
		return redirect("inventory/supply/$stocknumber/stockcard");
	}


	/**
	 * Show the form for releasing
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function releaseForm()
	{
		return view('stockcard.release')
				->with('type', 'stock')
				->with('title','Release');
	}


	/**
	 * Release the supply.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function release(Request $request)
	{
		$purchaseorder = $this->sanitizeString($request->get('purchaseorder'));
		$reference = $this->sanitizeString($request->get('reference'));
		$date = $this->sanitizeString($request->get('date'));
		$office = $this->sanitizeString($request->get('office'));
		$daystoconsume = $request->get("daystoconsume");
		$stocknumber = $request->get("stocknumber");
		$quantity = $request->get("quantity");

		DB::beginTransaction();

		foreach($stocknumber as $_stocknumber)
		{
			$validator = Validator::make([
				'Stock Number' => $stocknumber,
				'Requisition and Issue Slip' => $reference,
				'Date' => $date,
				'Issued Quantity' => $quantity["$_stocknumber"],
				'Office' => $office,
				'Days To Consume' => $daystoconsume
			],App\StockCard::$issueRules);

			$balance = App\Supply::findByStockNumber($_stocknumber)->stock_balance;
			if($validator->fails() || $quantity["$_stocknumber"] > $balance)
			{

				DB::rollback();

				if($quantity["$_stocknumber"] > $balance)
				{
					$validator = [ "You cannot release quantity of $_stocknumber which is greater than the remaining balance ($balance)" ];
				}

				return redirect("inventory/supply/stockcard/release")
						->with('total',count($stocknumber))
						->with('stocknumber',$stocknumber)
						->with('quantity',$quantity)
						->with('daystoconsume',$daystoconsume)
						->withInput()
						->withErrors($validator);
			}

			$transaction = new App\StockCard;
			$transaction->date = Carbon\Carbon::parse($date);
			$transaction->stocknumber = $_stocknumber;
			$transaction->reference = $reference;
			$transaction->organization = $office;
			$transaction->issued_quantity  = $quantity["$_stocknumber"];
			$transaction->daystoconsume = $daystoconsume["$_stocknumber"];
			$transaction->user_id = Auth::user()->id;
			$transaction->issue();
		}

		DB::commit();

		\Alert::success('Supplies Released')->flash();
		return redirect('inventory/supply');
	}

	public function printStockCard($stocknumber)
	{
		$orientation = 'Portrait';
		$supply = App\Supply::find($stocknumber);

		$data = [
			'supply' => $supply
		];

		$filename = "StockCard-".Carbon\Carbon::now()->format('mdYHm')."-$stocknumber".".pdf";
		//$view = "stockcard.print_index";
		$view = "stockcard.print_stockcard";

		return $this->printPreview($view,$data,$filename,$orientation);
	}

	public function printAllStockCard()
	{
		$orientation = 'Portrait';
		$supplies = App\Supply::all();

		$data = [
			'supplies' => $supplies
		];

		$filename = "StockCard-".Carbon\Carbon::now()->format('mdYHm').".pdf";
		$view = "stockcard.print_all_stockcards";
		return $this->printPreview($view,$data,$filename,$orientation);
	}

	public function estimateDaysToConsume(Request $request, $stocknumber)
	{
		return json_encode(App\StockCard::computeDaysToConsume($stocknumber));
	}

}
