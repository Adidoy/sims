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
    
    /**
    * {@inheritdoc}
    */
    public static function resolveId()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }

    public $appends = [
      'code', 'date_requested', 'date_released',  'remaining_days', 'expire_on'
    ];
  
    public function scopeFindOfficeRequest($query, $value)
    {
      $officeID = App\Office::where('code','=',$value)->pluck('id')->first();
      return $query->where('office_id','=',$officeID);
    }

    public function getCodeAttribute($value)
    {
      $date = Carbon\Carbon::parse($this->created_at);
      if(isset($this->local))
        $requestcode = $this->local;
      else{
      if (strlen($this->id) == 1) 
        $requestcode =  '000'.$this->id;
      elseif (strlen($this->id) == 2) 
        $requestcode =  '00'.$this->id;
      elseif (strlen($this->id) == 3) 
        $requestcode =  '0'.$this->id;
      elseif (strlen($this->id) == 4) 
        $requestcode =  $this->id;
      else
        $requestcode =  $this->id;
      }
      if(isset($this->local))
      return $requestcode;
      else
      return $date->format('y') . '-' .  $date->format('m') . '-' .  $requestcode;

    }

    public function getDateRequestedAttribute($value)
    {
      return Carbon\Carbon::parse($this->created_at)->format("F d Y h:m A");
    }

    public function getDateReleasedAttribute($value)
    {
      return isset($this->released_at) ? Carbon\Carbon::parse($this->released_at)->format("F d Y h:m A") : "N/A";  
    }

    public function getRemainingDaysAttribute()
    {
      if($this->approved_at == null)  return 'No Approval';
      if($this->approved_at != null && $this->released_at != null)  return 'Released';
      if(ucfirst($this->status) == 'Cancelled')  return 'Cancelled';
      if(ucfirst($this->status) == 'Disapproved')  return 'Disapproved';
      
      $approved_date = Carbon\Carbon::parse($this->approved_at);
      $date = Carbon\Carbon::now();
      
      return $approved_date->diffInDays($date);

    }

    public function getExpireOnAttribute()
    {
      if( $this->approved_at == null ) return 'No Approval';

      return Carbon\Carbon::parse($this->approved_at)->toFormattedDateString();
    }
}