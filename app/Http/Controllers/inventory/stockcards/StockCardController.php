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
		// try {
			$quantity = $request->get('quantity');
			$stocknumber = $request->get('stocknumber');
			$date = Carbon\Carbon::now();
			$reference = RequestCustodian::find($id)->code;
			foreach($stocknumber as $stocknumber) {
				$_quantity = $quantity["$stocknumber"];
				$newIssue = new StockCard;
				$validator = Validator::make([
					'Stock Number' => $stocknumber,
					'Requisition and Issue Slip' => $reference,
					'Date' => $date,
					'Issued Quantity' => $_quantity,
					'Office' => $office,
					'Days To Consume' => $_daystoconsume
				],$newIssue->rules(),$newIssue->messages());
		  
				$supply = App\Supply::findByStockNumber($stocknumber);
				$stock_balance = $supply->stock_balance;
		  
				$newIssue->date = $date;
				$newIssue->stocknumber = $stocknumber;
				$newIssue->reference = $reference;
				$newIssue->organization = $office;
				$newIssue->issued_quantity  = $_quantity;
				$newIssue->daystoconsume = $_daystoconsume;
				$newIssue->user_id = Auth::user()->id;
				$newIssue->issue();
			}
			DB::commit();
			\Alert::success('Stock Cards are now updated.')->flash();
		// } catch(\Exception $e) {
		// DB::rollback();
		// \Alert::error('An error occured! Please try again. Message: '.$e->getMessage())->flash();
		// }
	}
}
