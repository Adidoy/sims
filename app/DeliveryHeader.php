<?php

namespace App;

use Auth;
use DB;
use Carbon;
use Illuminate\Database\Eloquent\Model;

class DeliveryHeader extends Model
{
    protected $table = 'deliveries_header';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'local',
    	'supplier_id',
    	'purchaseorder_no',
    	'purchaseorder_date',
    	'invoice_no',
    	'invoice_date',
    	'delrcpt_no',
        'delivery_date',
        'received_by'
    ];

    protected $appends = [
        'date_invoice', 'date_delivered', 'date_purchaseorder', 'date_processed', 'supplier_name'
    ];

    public function rules() {
        return [
			'Purchase Order Number' => 'required',
			'Invoice Number' => 'required',
			'Delivery Receipt Number' => 'bail|required|unique:deliveries_header,delrcpt_no',
        ];
    }

    public function messages() {
        return [
			'Purchase Order Number.required' => 'APR/Purchase Order Number is required.',
			'Invoice Number.required' => 'Invoice Number is required.',
            'Delivery Receipt Number.required' => 'Delivery Reciept Number is required.',
            'Delivery Receipt Number.unique' => 'Delivery Reciept Number already exists.',
        ];
    }

    public function getDateInvoiceAttribute() {
        if($this->invoice_date == null || $this->invoice_date == "") return "None";
        return Carbon\Carbon::parse($this->invoice_date)->toFormattedDateString();
    }

    public function getDateDeliveredAttribute($value) {
        return Carbon\Carbon::parse($this->delivery_date)->toFormattedDateString();
    }

    public function getDatePurchaseorderAttribute($value) {
        return Carbon\Carbon::parse($this->purchaseorder_date)->toFormattedDateString();
    }

    public function getDateProcessedAttribute($value) {
        return Carbon\Carbon::parse($this->created_at)->format('d F Y h:i A');
    }

    public function getSupplierNameAttribute() {
        if(isset($this->supplier) && count($this->supplier) > 0):
            if($this->supplier->name)
                return $this->supplier->name;
        endif;
        return 'None';
    }

    public function scopeFindByLocal($query, $value) {
        return $query->where('local', '=', $value)->first();
    }

    public function scopeFindAllDeliveries($query) {
        return DB::table('deliveries_header')
            ->join('suppliers','suppliers.id','=','deliveries_header.supplier_id')
            ->whereNotIn('deliveries_header.id', DB::table('inspections')->pluck('id'))
            ->select('deliveries_header.id','deliveries_header.local', 'suppliers.name', 'deliveries_header.purchaseorder_no', 'deliveries_header.invoice_no', 'deliveries_header.delrcpt_no', 'deliveries_header.received_by', 'deliveries_header.created_at')
            ->get();
    }

    public function supplier() {
      return $this->belongsTo('App\Supplier','supplier_id','id');
    }

    public function supplies() {
        return $this->belongsToMany('App\Supply', 'deliveries_supplies',  'delivery_id', 'supply_id')
            ->withPivot('quantity_delivered', 'unit_cost');
    }

    public function inspection() {
        return $this->hasOne('App\Inspection', 'delivery_id');
    }

    public function scopeFindByNumber($query, $value) {
        return $query->where('number', '=', $value)->first();
    }
}