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

	protected $fillable = [ 'date','stocknumber','reference','receipt', 'received','issued','organization','daystoconsume']; 

	public function rules() {
		return [
			'Date' => 'required',
			'Stock Number' => 'required',
			'Reference' => 'required',
			'Office' => '',
			'Issued Quantity' => 'required|integer',
			'Days To Consume' => 'max:100'
		];
	}

	public function messages() {
		return [
			'Date.required' => 'Date is a required field.',
			'Stock Number.required' => 'Stock Number is a required field.',
			'Reference.required' => 'Reference is a required field.',
			'Office.required' => 'Office is a required field.',
			'Issued Quantity.required' => 'Issued Quantity is a required field.',
			'Issued Quantity.integer' => 'Issued Quantity must be represented in whole numbers only.',
			'Days To Consume.max' => 'Maximum days to consume is up to 100 only.'
		];
	}

}
