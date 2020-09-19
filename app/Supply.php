<?php
namespace App;

use DB;
use Carbon;
use App\Unit;
use App\Receipt;
use App\Request;
use App\Category;
use App\StockCard;
use App\LedgerCard;
use App\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Adjustments\AdjustmentDetails;

class Supply extends Model
{

	protected $table = 'supplies';
	protected $primaryKey = 'id';
	protected $fillable = ['stocknumber', 'details', 'unit_id', 'reorderpoint'];
	public $incrementing = false;
	public $timestamps = true;

	public function legitimateStockNumber()
	{
		return [
			'Stock Number' => 'required|exists:supplies,stocknumber'
		];
	}

	protected $appends = [
		'temp_balance',
		'stock_balance',
		'ledger_balance',
		'unit_name'
	];

	public function getUnitNameAttribute($value)
	{
		return (isset($this->unit) && count((array)$this->unit) > 0) ?  $this->unit->name : null;
	}

	// public function getUnitCostAttribute($value)
	// {
	// 	$cost = ReceiptSupply::findByStockNumber($this->stocknumber)
	// 							->where('remaining_quantity', '>', 0)
	// 							->whereNotNull('unitcost')
	// 							->select('unitcost')
	// 							->avg('unitcost');
		
	// 	return (count($cost) > 0) ? $cost : 0;
	// }

	public function getTempBalanceAttribute($value)
	{

		$balance = StockCard::findBySupplyId($this->id)
						->orderBy('date', 'desc')
						->orderBy('created_at', 'desc')
						->orderBy('id', 'desc')
						->pluck('balance_quantity')
						->first();

		$balance = (empty($balance) || $balance == null || $balance == '') ? 0 : $balance;

		$temporaryIssuedQuantity = DB::table('requests_supplies')
						->where('supply_id', '=', $this->id)
						->whereIn('request_id', Request::where('status', '=', 'approved')->pluck('id'))
						->sum('quantity_issued');

		return $balance - $temporaryIssuedQuantity;
	}

	public function getStockBalanceAttribute($value)
	{

		$balance = StockCard::findBySupplyId($this->id)
						->orderBy('date','desc')
						->orderBy('created_at','desc')
						->orderBy('id','desc')
						->pluck('balance_quantity')
						->first();

		$balance = (empty($balance) || $balance == null || $balance == '') ? 0 : $balance;
		return $balance;
	}

	public function getStockBalanceCostAttribute($value)
	{
		$balance = StockCard::findBySupplyId($this->id)
						->orderBy('id','desc')
						->pluck('balance_cost')
						->first();

		$balance = (empty($balance) || $balance == null || $balance == '') ? 0 : $balance;
		return $balance;
	}

	public function getLedgerBalanceAttribute($value)
	{
		$balance = LedgerCard::findBySupplyId($this->id)
						->orderBy('date','desc')
						->orderBy('created_at','desc')
						->orderBy('id','desc')
						->pluck('balance_quantity')
						->first();

		$balance = (empty($balance) || $balance == null || $balance == '') ? 0 : $balance;
		return $balance;
	}

	public function scopeIssued($query)
	{
		return $query->where('issuedquantity', '>', 0);
	}

	public function scopeFindByStockNumber($query,$value)
	{
		return $query->where('stocknumber', '=', $value)->first();
	}

	public function scopeFindByCategoryName($query, $value)
	{
		return $query->whereHas('category', function($query) use ($value) {
			$query->where('name', '=', $value);
		} );
	}

	public function scopeFindByCategoryId($query, $value)
	{
		return $query->where('category_id', '=', $value);
	}

	public function scopeStockNumber($query,$value)
	{
		return $query->where('stocknumber', '=', $value);
	}

	public function getUnitPriceAttribute($value)
	{
		return number_format($value, 2, '.', ',');
	}
	
	public function stockcards()
	{
		return $this->hasMany(StockCard::class, 'supply_id');
	}

	public function ledgercards()
	{
		return $this->hasMany(LedgerCard::class, 'supply_id');
	}

	public function category()
	{
		return $this->belongsTo(Category::class, 'category_id', 'id');
	}

	public function unit()
	{
		return $this->belongsTo(Unit::class, 'unit_id', 'id');
	}

	public function purchaseorders()
	{
		return $this->belongsToMany(PurchaseOrder::class, 'purchaseorders_supplies', 'supply_id', 'purchaseorder_id')
          ->withPivot('unitcost', 'received_quantity', 'ordered_quantity', 'remaining_quantity')
          ->withTimestamps();
	}

	public function receipts()
	{
		return $this->belongsToMany(Receipt::class, 'receipts_supplies', 'supply_id', 'receipt_id')
          ->withPivot('unitcost', 'quantity', 'remaining_quantity')
          ->withTimestamps();
	}

	public function requests()
	{
		return $this->belongsToMany(Request::class, 'requests_supplies', 'supply_id', 'request_id')
            ->withPivot('quantity_requested', 'quantity_issued', 'quantity_released', 'comments')
            ->withTimestamps();
	}

	public function adjustmentdetails()
	{
		return $this->belongsTo(Adjustment::class, 'supplies', 'adjustment_id', 'supply_id')
            ->withPivot('quantity', 'unit_cost', 'total_cost')
            ->withTimestamps();
	}
}
