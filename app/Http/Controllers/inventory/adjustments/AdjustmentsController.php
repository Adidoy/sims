<?php
namespace App\Http\Controllers\Inventory\Adjustments;

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
use App\Models\Inventory\Adjustments\Adjustment;
use App\Models\Inventory\Adjustments\AdjustmentDetails;
use App\Http\Controllers\Inventory\StockCards\StockCardController;


class AdjustmentsController extends Controller {

	public function index(Request $request)
	{
		if($request->ajax())
		{
			$adjustments = Adjustment::all();
			return datatables($adjustments)->toJson();
		}
		return view('adjustment.index')
					->with('title','Adjustment');
	}

	public function create()
	{
		return view('adjustment.create')
					->with('title','Adjustment')
					->with('action', 'create');
	}

	public function createIssue()
	{
		return view('adjustment.createissue')
					->with('title','Adjustment')
					->with('action', 'create');
	}

	public function receive(Request $request)	
	{
		$validator = Validator::make($request->all(), [
            'references' => 'required|max:255',
            'reasons' => 'required|max:255',
		]);

		if ($validator->fails()) {
            return redirect('inventory/adjustments/receive/create')
                        ->withErrors($validator)
                        ->withInput();
        }

		$stocknumbers = $request->get('stocknumber');
		$quantity = $request->get('quantity');
		$unitCosts = $request->get('unitcost');
		$references = $request->get('references');
		$reason = $request->get('reasons');
		$details = $request->get('details');
		$action = 'Receive';
		
		DB::beginTransaction();
			$adjustmentHeader = Adjustment::create([
				'local' => $this->generateLocalCode($request),
				'reference' => $references,
				'reasonLeadingTo' => $reason,
				'details' => $details,
				'action' => $action,
				'processed_by' => Auth::user()->id,
			]);

			foreach($stocknumbers as $stocknumber) {
				$supply = App\Supply::StockNumber($stocknumber)->first();
				AdjustmentDetails::create([
					'adjustment_id' => $adjustmentHeader->id,
					'supply_id' =>   $supply->id,
					'quantity' => $quantity["$stocknumber"],
					'unit_cost' => $unitCosts["$stocknumber"],
					'total_cost' => ($unitCosts["$stocknumber"] * $quantity["$stocknumber"])
				]);
			}
		DB::commit();
		$stockCardController = new StockCardController;
		$stockCardController->receiveAdjustmentSupplies($request, $adjustmentHeader->local);
		
		\Alert::success('Inventory Adjustment Recorded.')->flash();
		\Alert::success('Furnish duly signed copy of report by clicking View Details -> Download Adjustment Report.')->flash();
		return redirect('inventory/adjustments/');
	}

	public function issue(Request $request)	
	{
		$validator = Validator::make($request->all(), [
            'references' => 'required|max:255',
            'reasons' => 'required|max:255',
		]);

		if ($validator->fails()) {
            return redirect('inventory/adjustments/receive/create')
                        ->withErrors($validator)
                        ->withInput();
        }

		$stocknumbers = $request->get('stocknumber');
		$quantity = $request->get('quantity');
		$unitCosts = $request->get('unitcost');
		$references = $request->get('references');
		$reason = $request->get('reasons');
		$details = $request->get('details');
		$action = 'Issue';
		
		DB::beginTransaction();
			$adjustmentHeader = Adjustment::create([
				'local' => $this->generateLocalCode($request),
				'reference' => $references,
				'reasonLeadingTo' => $reason,
				'details' => $details,
				'action' => $action,
				'processed_by' => Auth::user()->id,
			]);

			foreach($stocknumbers as $stocknumber) {
				$supply = DB::table('supplies')->where('stocknumber', '=', $stocknumber)->first();
				$stockcard = DB::table('stockcards')->where('supply_id', '=', $supply->id)->orderBy('created_at', 'desc')->first();
				AdjustmentDetails::create([
					'adjustment_id' => $adjustmentHeader->id,
					'supply_id' =>   $supply->id,
					'quantity' => $quantity["$stocknumber"],
					'unit_cost' => $stockcard->balance_cost,
					'total_cost' => ($stockcard->balance_cost * $quantity["$stocknumber"])
				]);
			}
		DB::commit();
		$stockCardController = new StockCardController;
		$stockCardController->issueAdjustmentSupplies($request, $adjustmentHeader->local);
		
		\Alert::success('Inventory Adjustment Recorded.')->flash();
		\Alert::success('Furnish duly signed copy of report by clicking View Details -> Download Adjustment Report.')->flash();
		return redirect('inventory/adjustments/');
	}


	public function show(Request $request, $id) 
	{
		$adjustment = Adjustment::with('supplies')->find($id);
		//return $adjustment;
    	if($request->ajax()) {
			$supplies = $adjustment->supplies;
        	return json_encode([
				'data' => $supplies
		  	]);
		}
		return view('adjustment.show')
			->with('adjustment', $adjustment)
			->with('title','Supplies Delivery');
	}

	public function generateLocalCode(Request $request) 
	{
		$now = Carbon\Carbon::now();
		$const = $now->format('y') . '-' . $now->format('m');
		$id = count(Adjustment::get()) + 1;
	
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
		
		return 'ADJ-' . $const . '-' . $trxCode;
	}
}
