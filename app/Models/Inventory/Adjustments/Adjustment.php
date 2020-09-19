<?php

namespace App\Models\Inventory\Adjustments;

use App;
use Auth;
use Carbon;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    protected $table = 'adjustments_header';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = ['local', 'reference', 'reasonLeadingTo', 'details', 'action', 'processed_by'];
    protected $appends = ['processed_person', 'date_processed', 'details_append'];

    public function getDateProcessedAttribute($value)
    {
      return $this->created_at->format("d F Y h:m A");
    }

    public function getProcessedPersonAttribute() 
    {
      return isset($this->processed_by) ? strtoupper(App\User::find($this->processed_by)->fullname) : "None";
    }

    public function getDetailsAppendAttribute() 
    {
      return isset($this->details) ? $this->details : "N/A";
    }

    public function supplies()
  	{
  		return $this->belongsToMany(App\Supply::class,'adjustment_details', 'adjustment_id', 'supply_id')
            ->withPivot('quantity', 'unit_cost', 'total_cost');
  	}
}
