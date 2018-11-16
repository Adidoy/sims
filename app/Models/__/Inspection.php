<?php

namespace App;

use Carbon;
use DB;
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

	protected $appends = [
	 	'date_inspected', 'approval', 'approval_date', 'acknowledgement', 'acknowledgement_date', 'dai'
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

	public function getDateInspectedAttribute()
	{
		return isset($this->inspection_date) ? Carbon\Carbon::parse($this->inspection_date)->format('d F Y h:i A') : 'For Approval';
	}
	
	public function getApprovalAttribute()
	{
		return isset($this->inspection_approval) ? $this->inspection_approval : 'For Approval';
	}

	public function getApprovalDateAttribute()
	{
	 	return isset($this->inspection_approval_date) ? Carbon\Carbon::parse($this->inspection_approval_date)->format('d F Y h:i A') : 'N/A';
	}

	public function getAcknowledgementAttribute()
	{
	 	return isset($this->property_custodian_acknowledgement) ? $this->property_custodian_acknowledgement : 'For Acknowledgement';
	}

	public function getAcknowledgementDateAttribute()
	{
	 	return isset($this->property_custodian_acknowledgement_date) ? Carbon\Carbon::parse($this->property_custodian_acknowledgement_date)->format('d F Y h:i A')  : 'N/A';
	}

	public function getDaiAttribute()
	{
		return DeliveryHeader::where('id','=',$this->delivery_id)->pluck('local')->first();
	}

	public function scopeFindByInspectionID($query, $value)
	{
		return $query->where('id', '=', $value);
	}

	public function scopeFindAllDeliveries($query) {
        return DB::table('deliveries_header')
			->join('suppliers','suppliers.id','=','deliveries_header.supplier_id')
			->leftJoin('inspections', 'inspections.delivery_id', '=', 'deliveries_header.id')
			->leftJoin('users', 'deliveries_header.received_by', '=', 'users.id')
            ->whereNull('inspections.delivery_id')
            ->select('deliveries_header.id','deliveries_header.local', 'suppliers.name', 'deliveries_header.purchaseorder_no', 'deliveries_header.invoice_no', 'deliveries_header.delrcpt_no', DB::raw("CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) AS user_name"), 'deliveries_header.received_by', DB::raw("DATE_FORMAT(deliveries_header.created_at, '%d %M %Y %I:%i %p') AS date_processed"))
			->get();
    }

	public function delivery() 
	{
        return $this->hasOne('App\DeliveryHeader', 'id', 'delivery_id');
	}
	
	public function supplies()
	{
		return $this->belongsToMany('App\Supply', 'inspections_supplies',  'inspection_id', 'supply_id')
			->withPivot('unit_cost', 'quantity_passed', 'quantity_failed', 'comment');
	}

}
