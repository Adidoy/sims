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

    /**
    * {@inheritdoc}
    */
    public static function resolveId()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }

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

    public function rules() {
      return [
        'Purpose' => 'required|max:150',
      ];
    }

    public function messages() {
      return [
        'Purpose.required' => 'Please indicate the purpose of this supplies request.',
        'Purpose.max' => 'Purpose field is up to 150 characters only.'
      ];
    }
    
    public $appends = [
      'date_requested', 'date_released',  'remaining_days', 'expire_on', 'date_approved'
    ];
  
    public function scopeFindOfficeRequest($query, $value)
    {
      $officeID = App\Office::where('code','=',$value)->pluck('id')->first();
      return $query->where('office_id','=',$officeID);
    }

    public function getDateRequestedAttribute($value)
    {
      return Carbon\Carbon::parse($this->created_at)->format(" d F Y h:m A");
    }

    public function getDateReleasedAttribute($value)
    {
      return isset($this->released_at) ? Carbon\Carbon::parse($this->released_at)->format(" d F Y h:m A") : "N/A";  
    }

    public function getDateApprovedAttribute($value)
    {
      return isset($this->released_at) ? Carbon\Carbon::parse($this->approved_at)->format(" d F Y h:m A") : "N/A";  
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