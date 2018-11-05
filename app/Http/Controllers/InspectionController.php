<?php

namespace App\Http\Controllers;

use DB;
use App;
use Auth;
use Carbon;
use Validator;
use Illuminate\Http\Request;

class InspectionController extends Controller 
{

    public function index(Request $request) 
    {
        if($request->ajax()) {
            $deliveries = App\Inspection::findAllDeliveries();
			return datatables($deliveries)->toJson();
		}
        return view('inspection.supplies.index')
            ->with('title', 'Inspection');
    }

    public function show(Request $request, $id) 
    {
        $delivery = App\DeliveryHeader::with('supplies')->find($id);
        return view('inspection.supplies.inspect')
            ->with('delivery', $delivery);
    }

    public function showInspected(Request $request, $id=null) 
    {
        if($id == null) {
            if($request->ajax()) {
                $inspection = App\Inspection::get();
                return datatables($inspection)->toJson();
            }
            return view('inspection.supplies.show-inspected')
                ->with('title', 'Inspection');
        } else {
            $inspection = App\Inspection::with('supplies')->find($id);
            if($request->ajax()) {
                $supplies = $inspection->supplies;
                return json_encode([
                    'data' => $supplies
               ]);
            }
            return view('inspection.supplies.show-approval')
                ->with('inspection', $inspection)
                ->with('title', 'Inspection');
        }   
    }

    public function store(Request $request) 
    {
        $inspectionHeader = new App\Inspection;
        $inspector = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
        $delivery = App\DeliveryHeader::findByLocal($request->get("deliveryLocal"));
        $stocknumbers = $request->get("stocknumber");
        $quantity = $request->get("quantity");
        $unit_cost = $request->get("unit_cost");
		$quantity_passed = $request->get("quantity_passed");
        $comment = $request->get("passed_comment");
        
        DB::beginTransaction();
        $validator = Validator::make([
            'Remarks' => $request->get('remarks')
        ], $inspectionHeader->rules(), $inspectionHeader->messages());

        if($validator->fails()) {
            DB::rollback();
            return redirect('inspection/supply/'.$delivery->id.'/')
                ->withInput()
                ->withErrors($validator);
        }

        foreach($stocknumbers as $stocknumber) {
            if($quantity_passed["$stocknumber"] > $quantity["$stocknumber"]) {
                DB::rollback();
                return redirect('inspection/supply/'.$delivery->id.'/')
                    ->withInput()
                    ->withErrors(array('message' => 'Quantity Passed must not exceed the Quantity Delivered.'));
            }
            if($quantity_passed["$stocknumber"] < 0) {
                DB::rollback();
                return redirect('inspection/supply/'.$delivery->id.'/')
                    ->withInput()
                    ->withErrors(array('message' => 'Negative values are not allowed.'));
            }
            if(($comment["$stocknumber"] == null)||($comment["$stocknumber"] == '')||($comment["$stocknumber"] == ' ')) {
                DB::rollback();
                return redirect('inspection/supply/'.$delivery->id.'/')
                    ->withInput()
                    ->withErrors(array('message' => 'Please provide comments for each item inspected.'));
            }
        }

        $inspectionHeader = App\Inspection::create([
            'local' => $this->generateLocalCode($request),
            'delivery_id' => $delivery->id,
            'inspection_personnel' => $inspector,
            'inspection_date' => Carbon\Carbon::now(),
            'remarks' => $request->get('remarks')
        ]);
        foreach($stocknumbers as $stocknumber) {
            $supply = App\Supply::StockNumber($stocknumber)->first();
            App\InspectionSupplies::create([
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
		return redirect('/inspection/supply/');       
            
    }

    public function approveInspection(Request $request)
    {
        DB::beginTransaction();
            $inspection = App\Inspection::find($request->get('inspectionID'));
            $inspection->inspection_approval = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
            $inspection->inspection_approval_date = Carbon\Carbon::now();
            $inspection->save();
        DB::commit();
        \Alert::success('Inspection Report created successfully!')->flash();
		return redirect('/inspection/supply/');       
    }

    public function generateLocalCode(Request $request) 
    {
		$now = Carbon\Carbon::now();
		$const = $now->format('y') . '-' . $now->format('m');
		$id = count(App\Inspection::get()) + 1;
	
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
}
