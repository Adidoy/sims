<?php

namespace App\Models\Requests\Custodian;

use DB;
use App;
use Auth;
use Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\UserResolver;
use App\Models\Requests\Expiration\RequestExpiration;

class RequestCustodian extends Model implements Auditable, UserResolver
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

    public function approveRules() {
        return [
          'Remarks' => 'required|max:150',
        ];
      }
  
      public function approveMessages() {
        return [
          'Remarks.required' => 'Please put additional details for this supplies request.',
          'Remarks.max' => 'Remarks field is up to 150 characters only.'
        ];
      }

      public function releaseRules() {
        return [
          'Remarks' => 'required|max:150',
        ];
      }
  
      public function releaseMessages() {
        return [
          'Remarks.required' => 'Please indicate the name of the person whom the supplies are released to.',
          'Remarks.max' => 'Remarks field is up to 150 characters only.'
        ];
      }
      
    public $appends = [
      'office_name', 
      'date_approved', 
      'date_requested', 
      'date_released', 
      'date_updated',
      'date_cancelled',
      'date_expiry',
      'remaining_days', 
      'expire_on', 
      'request_person',  
      'issue_person', 
      'release_person', 
      'cancel_person',
      'request_remarks',
      'local_id',
    ];
    
    public function scopeFindOfficeRequest($query, $value)
    {
      $officeID = App\Office::where('code','=',$value)->pluck('id')->first();
      return $query->where('office_id','=',$officeID);
    }

    public function scopeFilterByRequestPeriod()
    {
      return $this
        ->distinct()
        ->select(DB::raw("MONTH(updated_at) 'month', YEAR(updated_at) 'year', CONCAT(MONTHNAME(updated_at), ' ', YEAR(updated_at)) 'fiscalyear'"))
        ->orderBy(DB::raw("year"),'desc')
        ->orderBy(DB::raw("month"),'desc');
    }

    public function getDateRequestedAttribute($value)
    {
      return $this->created_at->format("d F Y h:m A");
    }

    public function getDateReleasedAttribute($value)
    {
      return isset($this->released_at) ? Carbon\Carbon::parse($this->released_at)->format("d F Y h:m A") : "N/A";  
    }

    public function getDateApprovedAttribute($value)
    {
      return isset($this->approved_at) ? Carbon\Carbon::parse($this->approved_at)->format("d F Y h:m A") : "N/A";  
    }

    public function getDateUpdatedAttribute($value)
    {
      return isset($this->updated_at) ? Carbon\Carbon::parse($this->updated_at)->format("d F Y h:m A") : "N/A";  
    }

    public function getDateCancelledAttribute($value)
    {
      return isset($this->cancelled_at) ? Carbon\Carbon::parse($this->cancelled_at)->format("d F Y h:m A") : "N/A";  
    }

    public function getDateExpiryAttribute()
    {
      $expirationDate = RequestExpiration::where('request_id','=',$this->id)->pluck('expiration_date')->first();
      return isset($expirationDate) ? Carbon\Carbon::parse($expirationDate)->format("d F Y")." 05:00 PM" : "N/A";  
    }

    public function getLocalIdAttribute($value)
    {
      return isset($this->local) ? $this->local : "New Request";  
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

    public function getOfficeNameAttribute() 
    {
      return isset($this->office_id) ? strtoupper(App\Office::find($this->office_id)->name) : "None";
    }

    public function getRequestPersonAttribute() 
    {
      return isset($this->requestor_id) ? strtoupper(App\User::find($this->requestor_id)->fullname) : "None";
    }

    public function getIssuePersonAttribute() 
    {
      return isset($this->issued_by) ? strtoupper(App\User::find($this->issued_by)->fullname) : "None";
    }

    public function getReleasePersonAttribute() 
    {
      return isset($this->released_by) ? strtoupper(App\User::find($this->released_by)->fullname) : "None";
    }

    public function getCancelPersonAttribute() 
    {
      return isset($this->cancelled_by) ? $this->cancelled_by == 'SYSTEM' ? $this->cancelled_by : strtoupper(App\User::find($this->cancelled_by)->fullname) : "None";
    }

    public function getRequestRemarksAttribute() 
    {
      $remarks = $this->remarks;
      if (isset($remarks)) {
        if($remarks == "") {
          $remarks = "No Remarks";
        }
      } else {
        $remarks = "No Remarks";
      }
      return $remarks;
    }

    public function getStatusAttribute($value) 
    {
      if($value == null) return 'Pending';
      return ucfirst($value);
    }

    public function getExpireOnAttribute()
    {
      if( $this->approved_at == null ) return 'No Approval';

      return Carbon\Carbon::parse($this->approved_at)->toFormattedDateString();
    }

    public function supplies()
      {
          return $this->belongsToMany('App\Supply','requests_supplies', 'request_id', 'supply_id')
        ->withPivot('quantity_requested', 'quantity_issued', 'quantity_released', 'comments')
        ->withTimestamps();
    }
    
    public function office()
    {
      return $this->belongsTo('App\Office','office_id','id');
    }

    public function requestor()
    {
      return $this->belongsTo('App\User','requestor_id','id');
    }

}