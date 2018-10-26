<?php
namespace App\Http\Controllers;

use DB;
use App;
use PDF;
use Auth;
use Carbon;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class DeliveryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		if($request->ajax()) {
			$deliveries = App\DeliveryHeader::with('supplier')->get();
			return datatables($deliveries)->toJson();
		}
		return view('delivery.supplies.index')
			->with('title', 'Supply Delivery');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$supplier = App\Supplier::pluck('name','name');
		return view('delivery.supplies.accept')
			->with('title','Supply Delivery')
			->with('type', 'stock')
			->with('supplier',$supplier);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)	{
		$deliveryHeader = new App\DeliveryHeader;
		$supplier = App\Supplier::findBySupplierName($request->get("supplier"))->first();
		$userName = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
		$stocknumbers = $request->get("stocknumber");
		$quantity = $request->get("quantity");
		$unitcost = $request->get("unitcost");
	
		$validator = Validator::make([
			'Purchase Order Number' => $request->get('po_no'),
			'Invoice Number' => $request->get('invoice_no'),
			'Delivery Receipt Number' => $request->get('dr_no'),
		],$deliveryHeader->rules(),$deliveryHeader->messages());

		if($validator->fails()) {
			return redirect('delivery/supply/create')
				->withInput()
				->withErrors($validator);
		}
		
		DB::beginTransaction();
			$deliveryHeader = App\DeliveryHeader::create([
				'local' => $this->generateLocalCode($request, 'delivery'),
				'supplier_id' => $supplier->id,
				'purchaseorder_no' => $request->get('po_no'),
				'purchaseorder_date' => Carbon\Carbon::parse($request->get('po_date')),
				'invoice_no' => $request->get('invoice_no'),
				'invoice_date' => Carbon\Carbon::parse($request->get('invoice_date')),
				'delrcpt_no' => $request->get('dr_no'),
				'delivery_date' => Carbon\Carbon::parse($request->get('dr_date')),
				'received_by' => $userName
			]);
			foreach($stocknumbers as $stocknumber) {
				$supply = App\Supply::StockNumber($stocknumber)->first();
				App\DeliveriesDetail::create([
					'delivery_id' => $deliveryHeader->id,
					'supply_id' =>   $supply->id,
					'quantity_delivered' => $quantity["$stocknumber"],
					'unit_cost' => $unitcost["$stocknumber"]
				]);
			}
		DB::commit();
		\Alert::success('Supplies Delivery Recorded')->flash();
		return redirect('delivery/supply/');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id) {
		$id = $this->sanitizeString($id);
		$delivery = App\DeliveryHeader::with('supplies')->find($id);
    if($request->ajax()) {
        $supplies = $delivery->supplies;
        return json_encode([
           'data' => $supplies
		  	]);
		}
		return view('delivery.supplies.show')
			->with('delivery', $delivery)
			->with('title','Supplies Delivery');
	}

	public function generateLocalCode(Request $request, $trxType) {
		$now = Carbon\Carbon::now();
		$const = $now->format('y') . '-' . $now->format('m');

		if($trxType == 'delivery')
			$trx = App\DeliveryHeader::get();
		else
			$trx = App\Inspection::get();

		$id = count($trx) + 1;
	
		if (strlen($id) == 1) 
		  $trxCode =  '000'.$id;
		elseif (strlen($id) == 2) 
		  $trxCode =  '00'.$id;
		elseif (strlen($id) == 3) 
		  $trxCode =  '0'.$id;
		elseif (strlen($id) == 4) 
		  $trxCode =  $id;
		else
		  $trxCode =  $id;
		
		return 'DAI-' . $const . '-' . $trxCode;
	} 
}
