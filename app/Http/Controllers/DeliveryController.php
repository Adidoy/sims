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
		$this->validate($request, [
			'po_no' => 'required',
			'invoice_no' => 'required',
			'delrcpt_no' => 'unique:deliveries_header',
		]) ;

		App\DeliveryHeader::create([
			'po_no' => $po_no,
			'invoice_no' => $invoice_no,
			'delrcpt_no' => $dr_no,
		]);
		DB::beginTransaction();
			$supplier = App\Supplier::findBySupplierName($supplier)->first();
			$delivery_header = new App\DeliveryHeader;
			$delivery_header->local = $this->generateLocalCode($request);
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
				foreach($stocknumbers as $stocknumber) {
					$supply = App\Supply::StockNumber($stocknumber)->first();
					$deliveries_detail = new App\DeliveriesDetail;
					$deliveries_detail->delivery_id = $new_delivery->id;
					$deliveries_detail->supply_id = $supply->id;
					$deliveries_detail->quantity_delivered = $quantity["$stocknumber"];
					$deliveries_detail->unit_cost = $unitcost["$stocknumber"];
					$deliveries_detail->save();
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

	public function generateLocalCode(Request $request) {
		$deliveries = App\DeliveryHeader::get();
		$id = 1;
		$now = Carbon\Carbon::now();
		$const = $now->format('y') . '-' . $now->format('m');
	
		if(count($deliveries) > 0) {
		  $id = count($deliveries) + 1;
		}
		else {
		  $id = count(App\DeliveryHeader::get()) + 1;
		}
	
		if (strlen($id) == 1) 
		  $requestcode =  '000'.$id;
		elseif (strlen($id) == 2) 
		  $requestcode =  '00'.$id;
		elseif (strlen($id) == 3) 
		  $requestcode =  '0'.$id;
		elseif (strlen($id) == 4) 
		  $requestcode =  $id;
		else
		  $requestcode =  $id;
		
		return 'DAI-' . $const . '-' . $requestcode;
	} 
}
