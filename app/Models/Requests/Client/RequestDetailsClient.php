<?php

namespace App\Models\Requests\Client;

use App;
use Auth;
use Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\UserResolver;

class RequestDetailsClient extends Model implements Auditable, UserResolver
{
    use \OwenIt\Auditing\Auditable; 

    /**
    * {@inheritdoc}
    */
    public static function resolveId()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }

    protected $table = 'requests_supplies';
    public $timestamps = true;
    
    protected $fillable = [ 
      'supply_id' , 
      'request_id' , 
      'quantity_requested' ,
      'quantity_issued' , 
      'comments'
    ];

    public function requestDetailsRules() {
      return [
        'Stock Number' => 'required|exists:supplies,stocknumber',
        'Quantity' => 'required|integer|min:1',
      ];
    }

    public function requestDetailsMessages() {
      return [
        'Stock Number.required' => 'Stock number is required. Please check your request.',
        'Stock Number.exists' => 'Invalid stock number found. Please check your request.',
        'Quantity.min' => 'Minimum quantity is 1.',
        'Quantity.integer' => 'Quantity must be specified in whole numbers.'
      ];
    }
}