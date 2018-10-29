<?php

namespace App\Http\Controllers;

use DB;
use App;
use Auth;
use Carbon;
use Validator;
use Illuminate\Http\Request;

class InspectionController extends Controller {

    public function index(Request $request) {
        if($request->ajax()) {
            $deliveries = App\DeliveryHeader::findAllDeliveries();
			return datatables($deliveries)->toJson();
		}
        return view('inspection.supplies.index')
            ->with('title', 'Inspection');
    }

    public function show(Request $request, $id) {
        $delivery = App\DeliveryHeader::with('supplies')->find($id);
        return view('inspection.supplies.inspect')
            ->with('delivery', $delivery);
    }

    public function store(Request $request) {
        $inspectionHeader = new App\Inspection;
        $inspector = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
        $delivery = App\DeliveryHeader::findByLocal($request->get("deliveryLocal"));
        $stocknumbers = $request->get("stocknumber");
        $quantity = $request->get("quantity");
		$quantity_passed = $request->get("quantity_passed");
        $comment = $request->get("passed_comment");
        
        $validator = Validator::make([
            'Remarks' => $request->get('remarks')
        ], $inspectionHeader->rules(), $inspectionHeader->messages());

        if($validator->fails()) {
            return redirect(inspection.supplies.inspect)
                ->withInput()
                ->withErrors($validator);
        }

        DB::beginTransaction();
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
                    'quantity_passed' => $quantity_passed["$stocknumber"],
                    'quantity_failed' => $quantity["$stocknumber"] - $quantity_passed["$stocknumber"],
					'comment' => $comment["$stocknumber"]
                ]);
            }
        DB::commit();
        \Alert::success('Inspection Report created successfully!')->flash();
		return redirect('/inspection/supply/');       
            
    }

	public function generateLocalCode(Request $request) {
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

    public function getInitialVerifyForm()
    {
        return view('inspection.initial')
            ->with('title', 'Create');
    } 

    public function getApprovalForm(Request $request, $id)
    {

        if(!in_array(Auth::user()->access, [ 4, 5]))
        {
            \Alert::error('You do not have enough priviledge to access this page.')->flash();
            return back();   
        }

        $inspection = App\Inspection::with('supplies')->find($id);

        if(Auth::user()->access == 4 && (in_array($inspection->status, [ $this->status[0] ]) || $inspection->status == null ) )
        {
            $inspection->status = $this->status[1];
            $inspection->save();
        }

        if(Auth::user()->access == 5 && in_array($inspection->status, [ $this->status[2] ] ) )
        {
            $inspection->status = $this->status[3];
            $inspection->save();
        }

        return view('inspection.approval')
                    ->with('inspection', $inspection);

    }

    public function approval(Request $request, $id)
    {
        $id = $this->sanitizeString($id);
        $stocknumbers = $request->get("stocknumber");
        $quantity = $request->get("quantity");
        $received = $request->get("received");
        $remarks = $this->sanitizeString($request->get("remarks"));
        $action = $this->sanitizeString($request->get("action"));

        if(!in_array( Auth::user()->access, [ 4, 5] ) )
        {
            return view('errors.404');            
        }

        DB::beginTransaction();

        $inspection = App\Inspection::find($id);

        /**
         * check what actions the user has made to the inspection
         */
        if($action == 'passed'):
            $action = 'passed';
        elseif($action == 'failed'):
            $action = 'failed';
        else:
            $action = 'Pending';
        endif;

        /**
         * assign the status whether it is first or second inspection
         */
        
        if($action == 'passed')
        {
            if( $inspection->status == $this->status[1] && Auth::user()->access == 4)
            {
                $inspection->status = $this->status[2];
                $inspection->verified_by = Auth::user()->id;
                $inspection->verified_on = Carbon\Carbon::now();
            }

            if( $inspection->status == $this->status[3] && Auth::user()->access == 5)
            {
                $inspection->status = $this->status[4];
                $inspection->finalized_by = Auth::user()->id;
                $inspection->finalized_on = Carbon\Carbon::now();
            }
        }
        else
        {
            $inspection->status = $this->status[99];
        }

        $user_id = Auth::user()->id;

        $inspection->remarks()->save(
            new App\Remark([
                'title' => 'Additional Remarks',
                'description' => $remarks,
                'user_id' => $user_id
            ])
        );

        $inspection->save();

        /**
         * loops through each record
         * update the record in the database
         */
        foreach($stocknumbers as $stocknumber)
        {
             
            $_quantity = $this->sanitizeString($quantity["$stocknumber"]);
            $stocknumber = $this->sanitizeString($stocknumber);

            $supply = App\Supply::findByStockNumber($stocknumber);

            if($inspection->status == $this->status[2] && Auth::user()->access == 4)
            {
                $inspection->supplies()->updateExistingPivot( $supply->id, [
                    'quantity_adjusted' => $_quantity
                ]);
            }

            if($inspection->status == $this->status[4] && Auth::user()->access == 5)
            {
                $inspection->supplies()->updateExistingPivot( $supply->id, [
                    'quantity_final' => $_quantity
                ]);
            }
        }

        DB::commit();

        \Alert::success("Inspection " . ucfirst($action))->flash();
        return redirect('inspection');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    public function applyToStockCard(Request $request, $id)
    {
        $id = $this->sanitizeString($id);

        if(Auth::user()->access == 1)
        {
            $inspection = App\Inspection::find($id);
            $purchaseorder = $inspection->purchaseorder_number;
            $date = $inspection->date_received;
            $deliveryreceipt = $inspection->receipt_number;
            $invoice = $inspection->invoice;
            $invoice_date = $inspection->invoice_date;
            $dr_date = $inspection->date_delivered;
            $supplier = $inspection->supplier;
            $fundcluster = "";

            DB::beginTransaction();

            foreach($inspection->supplies as $supply)
            {
                $stocknumber = $supply->stocknumber;
                $daystoconsume = $supply->pivot->daystoconsume;
                $quantity = $supply->pivot->quantity_final;

                $validator = Validator::make([
                    'Stock Number' => $stocknumber,
                    'Purchase Order' => $purchaseorder,
                    'Date' => $date,
                    'Receipt Quantity' => $quantity,
                    'Office' => $supplier,
                    'Days To Consume' => $daystoconsume
                ],App\StockCard::$receiptRules);

                if($validator->fails())
                {
                    DB::rollback();
                    return back()->withErrors($validator);
                }

                /**
                 * save the record in the database
                 */
                $transaction = new App\StockCard;
                $transaction->date = Carbon\Carbon::parse($date);
                $transaction->invoice_date = Carbon\Carbon::parse($invoice_date);
                $transaction->dr_date = Carbon\Carbon::parse($dr_date);
                $transaction->stocknumber = $stocknumber;
                $transaction->reference = $purchaseorder;
                $transaction->receipt = $deliveryreceipt;
                $transaction->invoice = $invoice;
                $transaction->organization = $supplier;
                $transaction->fundcluster = $fundcluster;
                $transaction->received_quantity = $quantity;
                $transaction->daystoconsume = $daystoconsume;
                $transaction->user_id = Auth::user()->id;
                $transaction->receive();
            }

            $inspection->status = $this->status[5];
            $inspection->save();

            DB::commit();

            \Alert::success('The items has been added to Inventory')->flash();
        }

        return redirect('inspection');
    }

    public function print($id)
    {
        $id = $this->sanitizeString($id);
        $inspection = App\Inspection::with('supplies')->find($id);
        $row_count = 22;
        $adjustment = 4;
        $orientation = 'Portrait';
        if(isset($inspection->supplies)):
            $data_count = count($inspection->supplies) % $row_count;
            if($data_count == 0 || (($data_count < 5) && (count($inspection->supplies) > $row_count))):

              if((count($request->supplies) > $row_count) && ($data_count < 7)):
                $remaining_rows = $data_count + $row_count + $adjustment;
              else:
                $remaining_rows = 0;
              endif;
            else:
              $remaining_rows = $row_count - $data_count;
            endif;
        endif;

        $data = [
            'inspection' => $inspection,
            'row_count' => $row_count,
            'end' => $remaining_rows
        ];

        $filename = "Inspection-".Carbon\Carbon::now()->format('mdYHm').".pdf";
        $view = "inspection.print_show";

        return $this->printPreview($view,$data,$filename,$orientation);

    }
}
