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
        'purchaseorder_number', 'parsed_purchaseorder_date', 'supplier_name', 'invoice_code', 'parsed_invoice_date','parsed_date_delivered'
    ];

    public function getParsedInvoiceDateAttribute() {
        if($this->invoice_date == null || $this->invoice_date == "") return "None";
        return Carbon\Carbon::parse($this->invoice_date)->toFormattedDateString();
    }

    public function getParsedDateDeliveredAttribute($value) {
        return Carbon\Carbon::parse($this->delivery_date)->toFormattedDateString();
    }

    public function getParsedPurchaseorderDateAttribute($value) {
        return Carbon\Carbon::parse($this->purchaseorder_date)->toFormattedDateString();
    }

    public function getReceiptDateAttribute() {
        if($this->delivery_date == null || $this->delivery_date == "") return "None";

        return Carbon\Carbon::parse($this->delivery_date)->toFormattedDateString();
    }

    public function getInvoiceCodeAttribute() {
        if($this->invoice_no == null || $this->invoice_no == "") return "None";

        return $this->invoice_no;
    }

    // public function getDeliveriesAttribute() {
    //     return $this->deliverydetails;
    // }

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

    // public function getCodeAttribute($value) {
    //   $date = Carbon\Carbon::parse($this->created_at);
    //   if(isset($this->local))
    //     $requestcode = $this->local;
    //   else{
    //   if (strlen($this->id) == 1) 
    //     $requestcode =  '000'.$this->id;
    //   elseif (strlen($this->id) == 2) 
    //     $requestcode =  '00'.$this->id;
    //   elseif (strlen($this->id) == 3) 
    //     $requestcode =  '0'.$this->id;
    //   elseif (strlen($this->id) == 4) 
    //     $requestcode =  $this->id;
    //   else
    //     $requestcode =  $this->id;
    //   }
    //   if(isset($this->local))
    //   return $requestcode;
    //   else
    //   return $date->format('y') . '-' .  $date->format('m') . '-' .  $requestcode;

    // }

    public function supplier() {
      return $this->belongsTo('App\Supplier','supplier_id','id');
    }

    public function supplies() {
        return $this->belongsToMany('App\Supply', 'deliveries_details', 'delivery_id', 'stocknumber')
            ->withPivot('quantity_delivered', 'unit_cost');
    }

    public function scopeFindBySupplierName($query, $value)	{
		return $query->where('name', '=', $value);
	}

    public function scopeFindByNumber($query, $value) {
        return $query->where('number', '=', $value)->first();
    }
}