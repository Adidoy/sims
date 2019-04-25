<?php

namespace App\Models\Inventory\StockCards;

use DB;
use Auth;
use Event;
use Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\UserResolver;


class StockCard extends Model implements Auditable, UserResolver
{
    use \OwenIt\Auditing\Auditable;
    
	protected $auditInclude = [ 'date','stocknumber','reference','receipt', 'received','issued','organization','daystoconsume']; 
	
	/**
     * {@inheritdoc}
     */
    public static function resolveId()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }

	protected $table = 'stockcards';
	protected $primaryKey = 'id';
	public $fundcluster = null;
	public $timestamps = true;
	
	//protected $fillable = [ 'date','supply_id','reference','receipt', 'received_quantity','issued_quantity', 'organization','daystoconsume']; 

	public function rules() {
		return [
			'Date' => 'required',
			'Stock Number' => 'required',
			'Reference' => 'required',
			'Office' => '',
			'Issued Quantity' => 'required|integer'
		];
	}

	public function messages() {
		return [
			'Date.required' => 'Date is a required field.',
			'Stock Number.required' => 'Stock Number is a required field.',
			'Reference.required' => 'Reference is a required field.',
			'Office.required' => 'Office is a required field.',
			'Issued Quantity.required' => 'Issued Quantity is a required field.',
			'Issued Quantity.integer' => 'Issued Quantity must be represented in whole numbers only.'
		];
	}

	public function getParsedYearAttribute($value)
	{
		$date = Carbon\Carbon::parse($this->date);
		return $date->format('Y');
	}	

	public function scopeFilterByYearIssued()
	{
		return $this->where('issued_quantity','>','0')->distinct()->select(DB::raw("CONCAT(MONTHNAME(DATE), ' ', YEAR(DATE)) 'fiscalyear'"));
	}

	public function supply()
	{
		return $this->belongsTo('App\Supply','supply_id','id');
	}

}
