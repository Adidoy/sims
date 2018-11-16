<?php

namespace App;

use Carbon\Carbon;
use App\Models\Supply\Supply;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    protected $table = 'disposals';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    public $fillable = [
      'created_by', 'status', 'details'
    ];

    /**
     * Additional columns when selecting
     *
     * @var array
     */
    protected $appends = [
      'code', 'date_created'
    ];

    public static $rules = array(
      'Stock Number' => 'required|exists:supplies,stocknumber',
      'Quantity' => 'required|integer|min:1',
    );

    /**
     * Returns the details if its set
     *
     * @return void
     */
    public function getDetailsAttribute()
    {
        return isset($this->details) ? $this->details : 'Not Specified';
    }

    /**
     * Returns the code attribute from the id
     *
     * @param integer $value
     * @return string
     */
    public function getCodeAttribute($value)
    {
      $date = Carbon::parse($this->created_at);
      return $date->format('y') . '-' .  $date->format('m') . '-' .  $this->id;
    }

    /**
     * Returns the current status of the adjustment form
     *
     * @param [type] $value
     * @return void
     */
    public function getStatusAttribute($value)
    {
      if(isset($this->status)) {
          return isset($this->status);
      }

      $status = config('app.default_status');
    }

    /**
     * Fetch the total amount of each supply
     *
     * @return void
     */
    public function getTotalAmountAttribute()
    {
        $quantity = isset($this->pivot->attributes['quantity']) ? $this->pivot->attributes['quantity'] : 0;
        $unitcost = isset($this->pivot->attributes['unitcost']) ? $this->pivot->attributes['unitcost'] : 0;

        return $quantity * $unitcost;
    }

    /**
     * Fetch parsed date created
     *
     * @return object
     */
    public function getDateCreatedAttribute()
    {
      return Carbon::parse($this->created_at)->toDayDateTimeString();
    }

    /**
     * Fetch the supplies list
     *
     * @return object
     */
  	public function supplies()
  	{
        return $this->belongsToMany(Supply::class,'disposal_supply', 'disposal_id', 'supply_id')
              ->withPivot('quantity', 'unitcost')
              ->withTimestamps();
  	}
}
