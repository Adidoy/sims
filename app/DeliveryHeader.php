<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class DeliveryHeader extends Model
{
    protected $table = 'deliveries_header';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
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
        'parsed_date_delivered', 'purchaseorder_number', 'supplier_name', 'invoice_code', 'parsed_invoice_date', 'receipt_date'
    ];

    public function getParsedInvoiceDateAttribute() {
        if($this->invoice_date == null || $this->invoice_date == "") return "None";
        return Carbon\Carbon::parse($this->invoice_date)->toFormattedDateString();
    }

    public function getReceiptDateAttribute() {
        if($this->date_delivered == null || $this->date_delivered == "") return "None";

        return Carbon\Carbon::parse($this->date_delivered)->toFormattedDateString();
    }

    public function getInvoiceCodeAttribute() {
        if($this->invoice == null || $this->invoice == "") return "None";

        return $this->invoice;
    }

    public function getParsedDateDeliveredAttribute($value) {
        return Carbon\Carbon::parse($this->date_delivered)->toFormattedDateString();
    }

    public function getPurchaseorderNumberAttribute() {
        if(isset($this->purchaseorder) && count($this->purchaseorder) > 0):
            if($this->purchaseorder->number)
                return $this->purchaseorder->number;
        endif;

        return 'None';
    }

    public function getSupplierNameAttribute() {
        if(isset($this->supplier) && count($this->supplier) > 0):
            if($this->supplier->name)
                return $this->supplier->name;
        endif;
        return 'None';
    }

    public function getInvoiceAttribute() {
        if(!$this->attributes['invoice']) return 'Not Set';

        return $this->attributes['invoice'];
    }

    public function supplier() {
      return $this->belongsTo('App\Supplier','supplier_id','id');
    }

    public function setReceivedByAttribute($value) {
    	$this->received_by = Auth::user()->id;
    }

    public function scopeFindByNumber($query, $value) {
        return $query->where('number', '=', $value)->first();
    }
}