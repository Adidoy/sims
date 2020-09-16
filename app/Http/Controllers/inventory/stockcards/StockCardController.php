<?php
namespace App\Http\Controllers\Inventory\Stockcards;

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
use App\Models\Delivery\DeliveryHeader;
use App\Models\Delivery\DeliveriesDetail;
use App\Models\Inventory\StockCards\StockCard;
use App\Models\Requests\Custodian\RequestCustodian;

class StockCardController extends Controller 
{

	public function releaseSupplies($request, $id)
	{
		try {
			$quantity = $request->get('quantity');
			$stocknumber = $request->get('stocknumber');
			$date = Carbon\Carbon::now();
			$request = RequestCustodian::where('id','=',$id)->first();
			$office = $request->office->name;
			$reference = $request->local;
			foreach($stocknumber as $stocknumber) {
				$_quantity = $quantity["$stocknumber"];
				$newIssue = new StockCard;
				$validator = Validator::make([
					'Stock Number' => $stocknumber,
					'Requisition and Issue Slip' => $reference,
					'Date' => $date,
					'Issued Quantity' => $_quantity,
					'Office' => $office
				],$newIssue->rules(),$newIssue->messages());

				$supply = App\Supply::findByStockNumber($stocknumber);
				$stockBalance = $supply->stock_balance;
				$stockBalanceCost = $supply->stock_balance_cost;
				$stockTotalBalanceCost = ($stockBalance * $stockBalanceCost);
				$issuedQuantity = $quantity["$stocknumber"];
				$issuedCost = $stockBalanceCost;
				$totalIssuedCost = $quantity["$stocknumber"] * $stockBalanceCost;
				$newBalance = $stockBalance - $issuedQuantity;
				$newBalanceTotalCost = $stockTotalBalanceCost - $totalIssuedCost;
				$newBalanceUnitCost = $stockBalanceCost;
		  
				$newIssue->date = $date;
				$newIssue->supply_id = $supply->id;
				$newIssue->reference = $reference;
				$newIssue->organization = $office;
				$newIssue->received_quantity = 0;
				$newIssue->received_cost = 0;
				$newIssue->total_received_cost = 0;
				$newIssue->issued_quantity  = $issuedQuantity;
				$newIssue->issued_cost  = $issuedCost;
				$newIssue->total_issued_cost  = $totalIssuedCost;
				$newIssue->balance_quantity = $newBalance;
				$newIssue->balance_cost = $newBalanceUnitCost;
				$newIssue->total_balance_cost = $newBalanceTotalCost;
				$newIssue->daystoconsume = 0;
				$newIssue->user_id = Auth::user()->id;
				$newIssue->save();
			}
			DB::commit();
			\Alert::success('Stock Cards are now updated.')->flash();
		} catch(\Exception $e) {
		DB::rollback();
		\Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
		}
	}

	public function lateReleaseSupplies($request, $id)
	{
		try {
			$quantity = $request->get('quantity_released');
			$stocknumber = $request->get('stocknumber');
			$date = Carbon\Carbon::parse($request->get('date_released'));
			$user = $request->get('released_by');
			$request = RequestCustodian::where('id','=',$id)->first();
			$office = $request->office->name;
			$reference = $request->local;
			foreach($stocknumber as $stocknumber) {
				$_quantity = $quantity["$stocknumber"];
				$newIssue = new StockCard;
				$validator = Validator::make([
					'Stock Number' => $stocknumber,
					'Requisition and Issue Slip' => $reference,
					'Date' => $date,
					'Issued Quantity' => $_quantity,
					'Office' => $office
				],$newIssue->rules(),$newIssue->messages());

				$supply = App\Supply::findByStockNumber($stocknumber);
				$stockBalance = $supply->stock_balance;
				$stockBalanceCost = $supply->stock_balance_cost;
				$stockTotalBalanceCost = ($stockBalance * $stockBalanceCost);
				$issuedQuantity = $quantity["$stocknumber"];
				$issuedCost = $stockBalanceCost;
				$totalIssuedCost = $quantity["$stocknumber"] * $stockBalanceCost;
				$newBalance = $stockBalance - $issuedQuantity;
				$newBalanceTotalCost = $stockTotalBalanceCost - $totalIssuedCost;
				$newBalanceUnitCost = $stockBalanceCost;

				$newIssue->date = $date;
				$newIssue->supply_id = $supply->id;
				$newIssue->reference = $reference;
				$newIssue->organization = $office;
				$newIssue->received_quantity = 0;
				$newIssue->received_cost = 0;
				$newIssue->total_received_cost = 0;
				$newIssue->issued_quantity  = $issuedQuantity;
				$newIssue->issued_cost  = $issuedCost;
				$newIssue->total_issued_cost  = $totalIssuedCost;
				$newIssue->balance_quantity = $newBalance;
				$newIssue->balance_cost = $newBalanceUnitCost;
				$newIssue->total_balance_cost = $newBalanceTotalCost;
				$newIssue->daystoconsume = 0;
				$newIssue->user_id = $user;
				$newIssue->save();
			}
			DB::commit();
			\Alert::success('Stock Cards are now updated.')->flash();
		} catch(\Exception $e) {
		DB::rollback();
		\Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
		}
	}

	public function receiveSupplies($request, $id)
	{
		
		try {
			$quantity = $request->get('quantity');
			$unitCost = $request->get('unitcost');
			$stocknumber = $request->get('stocknumber');
			$date = Carbon\Carbon::now();
			$delivery = DeliveryHeader::where('id','=',$id)->first();
			$reference = $delivery->purchaseorder_no  .' / '. $delivery->invoice_no .' / '. $delivery->delrcpt_no;
			foreach($stocknumber as $stocknumber) {
				$newReceive = new StockCard;
				$validator = Validator::make([
					'Date' => $date,
					'Stock Number' => $stocknumber,
					'Purchase Order' => $delivery->purchaseorder_no,
					'Delivery Receipt' => $delivery->delrcpt_no,
					'Office' => '',
					'Receipt Quantity' => $quantity["$stocknumber"]
				],$newReceive->rules(),$newReceive->messages());

				$supply = App\Supply::findByStockNumber($stocknumber);
				$stockBalance = $supply->stock_balance;
				$stockBalanceCost = $supply->stock_balance_cost;
				$stockTotalBalanceCost = ($stockBalance * $stockBalanceCost);
				$receivedQuantity = $quantity["$stocknumber"];
				$receivedCost = $unitCost["$stocknumber"];
				$totalReceivedCost = $quantity["$stocknumber"] * $unitCost["$stocknumber"];
				$newBalance = $stockBalance + $receivedQuantity;
				$newBalanceTotalCost = $stockTotalBalanceCost + $totalReceivedCost;
				$newBalanceUnitCost = $newBalanceTotalCost / $newBalance;
		  
				$newReceive->date = $date;
				$newReceive->supply_id = $supply->id;
				$newReceive->reference = $reference;
				$newReceive->organization = $request->get("supplier");
				$newReceive->received_quantity = $receivedQuantity;
				$newReceive->received_cost = $receivedCost;
				$newReceive->total_received_cost = $totalReceivedCost;
				$newReceive->issued_quantity = 0;
				$newReceive->issued_cost = 0;
				$newReceive->total_issued_cost = 0;
				$newReceive->balance_quantity = $newBalance;
				$newReceive->balance_cost = $newBalanceUnitCost;
				$newReceive->total_balance_cost = $newBalanceTotalCost;
				$newReceive->daystoconsume = 0;
				$newReceive->user_id = Auth::user()->id;
				$newReceive->save();
			}
			DB::commit();
			\Alert::success('Stock Cards are now updated.')->flash();
		} catch(\Exception $e) {
		DB::rollback();
		\Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
		}
	}
}