<?php

namespace App;

use Carbon;
use Illuminate\Database\Eloquent\Model;

class InspectionSupplies extends Model {
	
	protected $table = 'inspections_supplies';
	protected $primaryKey = 'id';
	public $timestamps = true;

	protected $fillable = [ 
		'inspection_id',
		'supply_id',
		'quantity_passed',
		'quantity_failed',
		'comment'
	]; 

	public function rules() {
        return [
            'Quantity Passed' => 'required',
			'Comment' => 'required'
        ];
    }

    public function messages() {
        return [
            'Quantity Passed.required' => 'Quantity Passed is required.',
			'Remarks.required' => 'Please provide remarks for this report.'
        ];
	}
	
}