<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class Inspection extends Model
{
  protected $table = 'inspections';
  protected $primaryKey = 'id';
	public $timestamps = true;
	public $supply_list = [];

	protected $fillable = [ 
		'local',
		'inspection_personnel',
		'inspection_date',
		'remarks',
		'inspection_approval', 
		'inspection_approval_date',
		'property_custodian_acknowledgement',
		'property_custodian_acknowledgement_date'
	]; 

	public static $status_list = [
        0 => 'Pending',
        1 => '1st Inspection',
        2 => 'Passed 1st Inspection',
        3 => 'Final Inspection',
        4 => 'Passed Final Inspection',
        5 => 'Applied To Stock Card',
        99 => 'Failed'
    ];

	public static $inspectionRules = array(
		'Date' => 'required',
		'Stock Number' => 'required',
		'Purchase Order' => 'nullable',
		'Delivery Receipt' => 'nullable',
		'Office' => '',
		'Receipt Quantity' => 'required|integer',
		'Days To Consume' => 'max:100'
	);

	public function rules() {
        return [
			'Remarks' => 'required'
        ];
    }

    public function messages() {
        return [
			'Remarks.required' => 'Please provide remarks for this report.'
        ];
    }

	protected $appends = [
		'code', 'inspector_name'
	];

	public function getInspectorNameAttribute()
	{
		return User::find($this->received_by)->fullname;
	}

  	public function supplies()
  	{
  		return $this->belongsToMany('App\Supply','inspections_supplies', 'inspection_id', 'supply_id')
            ->withPivot('quantity_received', 'quantity_adjusted', 'quantity_final', 'daystoconsume')
            ->withTimestamps();
  	}

  	public function remarks()
  	{
  		return $this->hasMany('App\Remark', 'inspection_id', 'id');
  	}

  	public function getCodeAttribute()
  	{
  		$date = Carbon\Carbon::now();
  		return $date->format('y') . '-' . $date->format('m') . '-' . $this->id;
  	}

  	public function initialize()
  	{
  		$this->save();

  		$this->supplies()->sync($this->supply_list);
  	}
}
