<?php

namespace App\Models\Requests\Client;

use App;
use Auth;
use Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\UserResolver;

class RequestClient extends Model implements Auditable, UserResolver
{
    use \OwenIt\Auditing\Auditable; 

    protected $table = 'requests';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    public $expire_before = 3;
    protected $fillable = [ 
      'local' , 
      'requestor_id' , 
      'office_id' ,
      'issued_by' , 
      'remarks'  , 
      'purpose'  , 
      'status' 
    ];

    protected $auditInclude = [ 
      'local' , 
      'requestor_id' , 
      'office_id' ,
      'issued_by' , 
      'remarks'  , 
      'purpose'  , 
      'status' 
    ];
    
    public static $status_list = [
      0 => 'pending',
      1 => 'approved',
      2 => 'disapproved',
      3 => 'request expired',
      4 => 'cancelled',
      5 => 'released'
    ];

    public function updateRules() {
      return [
        'Stock Number' => 'required|exists:supplies,stocknumber',
        'Quantity' => 'required|integer|min:1',
        'Purpose' => 'required',
      ];
    }

    public static $cancelRules = array(
      'Details' => 'required',
    );

    public function commentsRules(){
      return [
        'Details' => 'required|max:100'
      ];
    }
    
    public $appends = [
      'code', 'date_requested', 'date_released',  'remaining_days', 'expire_on', 'office_code'
    ];
  
    public function scopeFindOfficeRequest($query, $value)
    {
      $officeID = App\Office::where('code','=',$value)->pluck('id')->first();
      return $query->where('office_id','=',$officeID);
    }

    public function getOfficeCodeAttribute()
    {
      return $this->office->code;
    }
     
}