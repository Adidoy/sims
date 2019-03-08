<?php

namespace App\Http\Controllers\Inspection;

use DB;
use App;
use Auth;
use Carbon;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inspection\Inspection;
use App\Models\Delivery\DeliveryHeader;
use App\Models\Inspection\InspectionSupplies;

class InspectionController extends Controller 
{

    public function index(Request $request) 
    {
        $deliveries = Inspection::findAllDeliveries();
        if($request->ajax()) {
			return datatables($deliveries)->toJson();
		}
        return view('inspection.supplies.forms.index')
            ->with('title', 'Inspection');
    }

    public function show(Request $request, $id) 
    {
        $delivery = DeliveryHeader::with('supplies')->find($id);
        return view('inspection.supplies.forms.inspect')
            ->with('delivery', $delivery);
    }

    public function showInspected(Request $request, $id=null) 
    {
        if($id == null) {
            if($request->ajax()) {
                $inspection = Inspection::get();
                return datatables($inspection)->toJson();
            }
            return view('inspection.supplies.forms.show-inspected')
                ->with('title', 'Inspection');
        } else {
            $inspection = Inspection::with('supplies')->find($id);
            if($request->ajax()) {
                $supplies = $inspection->supplies;
                return json_encode([
                    'data' => $supplies
                ]);
            }
            return view('inspection.supplies.forms.show-approval')
                ->with('inspection', $inspection)
                ->with('title', 'Inspection');
        }   
    }  

    public function store(Request $request) 
    {
        $remarks = "Complete";

        $inspectionHeader = new Inspection;
        $inspector = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
        $delivery = DeliveryHeader::findByLocal($request->get("deliveryLocal"));
        $stocknumbers = $request->get("stocknumber");
        $quantity = $request->get("quantity");
        $unit_cost = $request->get("unit_cost");
		$quantity_passed = $request->get("quantity_passed");
        $comment = $request->get("passed_comment");
        
        DB::beginTransaction();

        foreach($stocknumbers as $stocknumber) {
            if($quantity_passed["$stocknumber"] > $quantity["$stocknumber"]) {
                DB::rollback();
                return redirect('inspection/supplies/'.$delivery->id.'/')
                    ->withInput()
                    ->withErrors(array('message' => 'Quantity Passed must not exceed the Quantity Delivered.'));
            }
            if($quantity_passed["$stocknumber"] < 0) {
                DB::rollback();
                return redirect('inspection/supplies/'.$delivery->id.'/')
                    ->withInput()
                    ->withErrors(array('message' => 'Negative values are not allowed.'));
            }
            if(($comment["$stocknumber"] == null)||($comment["$stocknumber"] == '')||($comment["$stocknumber"] == ' ')) {
                DB::rollback();
                return redirect('inspection/supplies/'.$delivery->id.'/')
                    ->withInput()
                    ->withErrors(array('message' => 'Please provide comments for each item inspected.'));
            }
        }

        foreach($stocknumbers as $stocknumber) {
            if($quantity_passed["$stocknumber"] <> $quantity["$stocknumber"]) {
                $remarks = "Partial";
                break;
            }
        }

        $inspectionHeader = Inspection::create([
            'local' => $this->generateLocalCode($request),
            'delivery_id' => $delivery->id,
            'inspection_personnel' => $inspector,
            'inspection_date' => Carbon\Carbon::now(),
            'remarks' => $remarks
        ]);
        foreach($stocknumbers as $stocknumber) {
            $supply = App\Supply::StockNumber($stocknumber)->first();
            InspectionSupplies::create([
                'inspection_id' => $inspectionHeader->id,
                'supply_id' =>   $supply->id,
                'unit_cost' =>   $unit_cost["$stocknumber"],
                'quantity_passed' => $quantity_passed["$stocknumber"],
                'quantity_failed' => $quantity["$stocknumber"] - $quantity_passed["$stocknumber"],
                'comment' => $comment["$stocknumber"]
            ]);
        }
        DB::commit();
        \Alert::success('Inspection Report created successfully!')->flash();
		return redirect('/inspection/supplies/');       
    }

    public function approveInspection(Request $request, $id, $action)
    {
        if ($action == "approve") {
            DB::beginTransaction();
                $inspection = Inspection::find($id);
                $inspection->inspection_approval = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
                $inspection->inspection_approval_date = Carbon\Carbon::now();
                $inspection->save();
            DB::commit();
            \Alert::success('Inspection Report is approved!')->flash();
            return redirect('/inspection/supplies/view'); 
        } else {
            try{
                DB::beginTransaction();
                    $inspection = Inspection::with('supplies')->find($id);
                    $inspection->property_custodian_acknowledgement = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
                    $inspection->property_custodian_acknowledgement_date = Carbon\Carbon::now();
                    $inspection->save();
                    $delivery = DeliveryHeader::find($inspection->delivery_id);
                    foreach($inspection->supplies as $supply) {
                        $balance = App\StockCard::findBalanceQuantity($supply->id);
                        $stockCard = new App\StockCard;
                        $stockCard->date = Carbon\Carbon::now();
                        $stockCard->supply_id = $supply->id;
                        $stockCard->reference = "PO/APR No.: " . $delivery->purchaseorder_no . " / Invoice No.: " . $delivery->invoice_no;
                        $stockCard->receipt = "Delivery: " . $delivery->local . " / Inspection: " . $inspection->local;
                        $stockCard->organization = 'None';
                        $stockCard->received_quantity = $supply->pivot->quantity_passed;
                        $stockCard->issued_quantity = 0;
                        $stockCard->balance_quantity = $balance->balance_quantity + $supply->pivot->quantity_passed;
                        $stockCard->daystoconsume = 'N/A';
                        $stockCard->user_id = Auth::user()->id;
                        $stockCard->save();
                    }
                DB::commit();
                \Alert::success('Inspection Report is is acknowledged! Stock cards updated!')->flash();
                return redirect('/inspection/supplies/view'); 
            } catch(\Exception $e) {
                DB::rollback();
                \Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
                return redirect('/inspection/supplies/view'); 
            }
        }
      
    }

    public function generateLocalCode(Request $request) 
    {
		$now = Carbon\Carbon::now();
		$const = $now->format('y') . '-' . $now->format('m');
		$id = count(Inspection::get()) + 1;
	
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
		
		return 'IAR-' . $const . '-' . $trxCode;
    }
    
    public function print(Request $request, $id)
	{
		$orientation = 'Portrait';
        $inspection = Inspection::with('delivery')->with('supplies')->find($id);
		$data = [
			'inspection' => $inspection
		];

		$filename = "Inspection and Acceptance Report - ".$inspection->local." - ".Carbon\Carbon::now()->format('mdYHm').".pdf";
		$view = "inspection.supplies.reports.inspection_report";
		return $this->printPreview($view,$data,$filename,$orientation);
	}
}
