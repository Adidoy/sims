<?php
namespace App\Http\Controllers;

use App;
use Validator;
use Carbon;
use DB;
use Auth;
use PDF;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers;
use App\Models\Requests\LateEntry\RequestClient;
use App\Models\Requests\Signatory\RequestSignatory;
use App\Models\Requests\LateEntry\RequestDetailsClient;

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
		$issued_by = DB::table('users')->where('office', '=', 'PSMO')->select('id', DB::raw("CONCAT(firstname,' ',lastname) AS fullname"))->pluck('fullname', 'id');
		$released_by = DB::table('users')->where('office', '=', 'PSMO')->select('id', DB::raw("CONCAT(firstname,' ',lastname) AS fullname"))->pluck('fullname', 'id');
		return view('stockcard.release')
				->with('type', 'stock')
				->with('date', Carbon\Carbon::now())
				->with('issued_by', $issued_by)
				->with('released_by', $released_by)
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
		$date_requested = $request->get('date_requested');
		$date_released = $request->get('date_released');
		$date_issued = $request->get('date_issued');
		$office = $request->get('office');
		$stocknumbers = $request->get("stocknumber");
		$quantity_requested = $request->get("quantity_requested");
		$quantity_released = $request->get("quantity_released");
		$office = App\Office::where("code", "=", $request->get("office"))->first()->id;
		$issued_by = $request->get("issued_by");
		$released_by = $request->get("released_by");
		$requested_by = DB::table('users')->where('office', '=', $request->get("office"))->orderBy('id', 'DESC')->first()->id;
		$reference = $this->generateCode();
		
		foreach($stocknumbers as $stocknumber)
		{
			// $validator = Validator::make([
			// 	'Stock Number' => $stocknumber,
			// 	'Requisition and Issue Slip' => $reference,
			// 	'Date' => $date_issued,
			// 	'Issued Quantity' => $quantity_released["$stocknumber"],
			// 	'Office' => $office
			// ],App\StockCard::$issueRules);$validator->fails() || 

			$balance = App\Supply::findByStockNumber($stocknumber)->stock_balance;
			if($quantity_released["$stocknumber"] > $balance)
			{
				if($quantity_released["$stocknumber"] > $balance)
				{
					$validator = [ "You cannot release quantity of $stocknumber which is greater than the remaining balance ($balance)" ];
				}

				return redirect("inventory/supply/stockcard/release")
						->with('stocknumber',$stocknumber)
						->with('quantity_requested',$quantity_requested)
						->with('quantity_released',$quantity_released)
						->withInput()
						->withErrors($validator);
			}
		}

        //try{
            DB::beginTransaction();
            $newRequest = RequestClient::create([
				'local'=> $reference,
				'requestor_id' => $requested_by,
				'office_id' => $office,
				'issued_by' => $issued_by,
				'remarks' => 'Late Entry for RIS in the System.',
				'purpose' => 'Late Entry for RIS in the System.',
				'status' => 'released',
				'released_by' => $released_by,
				'released_at' => Carbon\Carbon::parse($date_released),
				'approved_at' => Carbon\Carbon::parse($date_issued),
				'created_at' => Carbon\Carbon::parse($date_requested),
				'updated_at' => Carbon\Carbon::now()
            ]);
            foreach($stocknumbers as $stocknumber) {
                $newRequestDetails = RequestDetailsClient::create([
                    'supply_id' => App\Supply::stockNumber($stocknumber)->first()->id, 
                    'request_id' => $newRequest->id, 
					'quantity_requested' => $quantity_requested["$stocknumber"],
					'quantity_issued' => $quantity_released["$stocknumber"],
					'quantity_released' => $quantity_released["$stocknumber"],
                ]);
			}

			DB::commit();

			$office = App\Office::where('id','=',$office)->first();
			if(!isset($office->head_office)) {
				$headOffice = $office;
				$office = App\Office::where('code','=',$office->code.'-A'.$office->code)->first();
			} else {
				$headOffice = App\Office::where('id','=',$office->head_office)->first();
				while(isset($headOffice->head_office)) {
					$office = $headOffice;
					$headOffice = App\Office::where('id','=',$headOffice->head_office)->first();
				}
			}
		
			$sector = $headOffice;
			$signatory = new RequestSignatory;
			$signatory->request_id = $newRequest->id;
			$signatory->requestor_name = isset($office->name) ? $office->head != "None" ?$office->head : "" : "";
			$signatory->requestor_designation = isset($office->name) ? $office->head_title != "None" ? $office->head_title : "" : "";
			$signatory->approver_name = isset($sector->name) ? $sector->head : $newRequest->office->head;
			$signatory->approver_designation = isset($sector->head) ? $sector->head_title : $newRequest->office->head_title;
			$signatory->save();

			$stockCardController = new Inventory\Stockcards\StockCardController;
			$stockCardController->lateReleaseSupplies($request, $newRequest->id);
            
            \Alert::success('Late Entry of RIS is now saved.')->flash();
            return redirect('/');
        // } catch(\Exception $e) {
        //  DB::rollback();
        //  \Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
        //  return redirect('inventory/supply/stockcard/release'); 
        // }
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

	public function generateCode() 
	{
	  $requests = App\Models\Requests\Custodian\RequestCustodian::whereNotNull('local')->orderBy('created_at','desc')->first();
	  $id = substr($requests->local,6,4) + 1;
	  $now = Carbon\Carbon::now();
	  $year = substr($requests->local,0,2);
	  if($year != $now->format('y')) {
		$id = 1;
	  }
	  
	  $const = $now->format('y') . '-' . $now->format('m');
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
}
