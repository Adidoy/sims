<?php

namespace App;

use Carbon;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model {
	
	protected $table = 'inspections';
	protected $primaryKey = 'id';
	public $timestamps = true;
	public $supply_list = [];

	protected $fillable = [ 
		'local',
		'delivery_id',
		'inspection_personnel',
		'inspection_date',
		'remarks',
		'inspection_approval', 
		'inspection_approval_date',
		'property_custodian_acknowledgement',
		'property_custodian_acknowledgement_date'
	]; 

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
	
    public function delivery() {
        return $this->hasOne('App\DeliveryHeader');
    }

}
