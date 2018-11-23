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
use App\Models\Inventory\StockCards\StockCard;
use App\Models\Requests\Custodian\RequestCustodian;

class StockCardController extends Controller {

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
				$_quantity = 
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
		  
				$newIssue->date = $date;
				$newIssue->supply_id = $supply->id;
				$newIssue->reference = $reference;
				$newIssue->organization = $office;
				$newIssue->received_quantity = 0;
				$newIssue->issued_quantity  = $quantity["$stocknumber"];
				$newIssue->balance_quantity = $stockBalance - $quantity["$stocknumber"];
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
}
