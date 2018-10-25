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
class StockCardController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $id)
	{
		$stockcard = App\StockCard::findBySupplyId($id)
		
		->orderBy('reference', 'asc')
				->orderBy('created_at','desc')
				->get();
		//$stockcard = $stockcard->sortByDesc('created_at');
		//return $stockcard;
		if($request->ajax())
		{
			return datatables($stockcard)->toJson();
		}

		$supply = App\Supply::find($id);
		return View('stockcard.index')
				->with('supply',$supply)
				->with('title',$supply->stocknumber);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$supplier = App\Supplier::pluck('name','name');
		return view('stockcard.accept')
			->with('title','Accept')
			->with('type', 'stock')
			->with('supplier',$supplier);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$array = [];
		$purchaseorder = $this->sanitizeString($request->get('purchaseorder'));
		$deliveryreceipt = $this->sanitizeString($request->get('receipt'));
		$date = $this->sanitizeString($request->get('date'));
		$office = $this->sanitizeString($request->get('office'));
		$supplier = $this->sanitizeString($request->get("supplier"));
		$daystoconsume = $request->get("daystoconsume");
		$stocknumbers = $request->get("stocknumber");
		$quantity = $request->get("quantity");
		$dr_date = $this->sanitizeString($request->get('receipt-date'));
		$invoice = $this->sanitizeString($request->get('invoice'));
		$invoice_date = $this->sanitizeString($request->get('invoice-date'));
		$fundcluster = $this->sanitizeString($request->get("fundcluster"));
		$remarks = $this->sanitizeString($request->get('remarks'));

		$username = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;

		$date = Carbon\Carbon::parse($date);

		DB::beginTransaction();

		if(!is_array($stocknumbers) && count($stocknumbers) <= 0) return back()->withInput()->withErrors(['Invalid information supplied']);

		/**
		 * set the reference as physical count if the item is physically counted
		 * check if the physical attribute is included in the sent request
		 * if sent, set the reference and receipt as physical count
		 * set the date as today
		 */
		if($request->has('physical'))
		{
			$deliveryreceipt = $purchaseorder = 'Physical Inventory';
			$dr_date = $date = $invoice_date = Carbon\Carbon::now();
		}

		$inspection = new App\Inspection;
		$inspection->purchaseorder_number = $purchaseorder ;
		$inspection->date_received = Carbon\Carbon::parse($date) ;
		$inspection->receipt_number = $deliveryreceipt ;
		$inspection->invoice = $invoice ;
		$inspection->invoice_date = Carbon\Carbon::parse($invoice_date) ;
		$inspection->date_delivered = Carbon\Carbon::parse($dr_date) ;
		$inspection->supplier = $supplier ;
		$inspection->received_by = Auth::user()->id;
		$inspection->status = 'Pending';	

		foreach($stocknumbers as $stocknumber)
		{
			$validator = Validator::make([
				'Stock Number' => $stocknumber,
				'Purchase Order' => $purchaseorder,
				'Date' => $date,
				'Receipt Quantity' => $quantity["$stocknumber"],
				'Office' => $office,
				'Days To Consume' => $daystoconsume["$stocknumber"]
			],App\StockCard::$receiptRules);

			if($validator->fails())
			{
				DB::rollback();
				return redirect("inventory/supply/stockcard/accept")
						->with('total',count($stocknumbers))
						->with('stocknumber',$stocknumbers)
						->with('quantity',$quantity)
						->with('daystoconsume',$daystoconsume)
						->withInput()
						->withErrors($validator);
			}

			/**
			 * save the record in the database
			 */
			$transaction = new App\StockCard;
			$transaction->date = Carbon\Carbon::parse(Carbon\Carbon::now());
			$transaction->invoice_date = Carbon\Carbon::parse($invoice_date);
			$transaction->dr_date = Carbon\Carbon::parse($dr_date);
			$transaction->stocknumber = $stocknumber;
			$transaction->reference = $purchaseorder;
			$transaction->receipt = $deliveryreceipt;
			$transaction->invoice = $invoice;
			$transaction->organization = $supplier;
			$transaction->fundcluster = $fundcluster;
			$transaction->received_quantity = $quantity["$stocknumber"];
			$transaction->daystoconsume = $daystoconsume["$stocknumber"];
			$transaction->user_id = Auth::user()->id;
			$transaction->receive();
			$supply = App\Supply::findByStockNumber($stocknumber);

			$array[$supply->id] = [
				'quantity_received' => $quantity["$stocknumber"],
				'daystoconsume' => $daystoconsume["$stocknumber"]
			];
		}

		$inspection->supply_list = $array;
		$inspection->initialize();

		DB::commit();
		\Alert::success('Supplies Received')->flash();
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
