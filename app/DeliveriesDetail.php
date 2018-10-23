<?php

namespace App;

use App\Supply;
use App\DeliveryHeader;
use Illuminate\Database\Eloquent\Model;

class DeliveriesDetail extends Model
{
    protected $table = 'deliveries_details';
    protected $primaryKey = 'delivery_id';
    protected $fillable = [
    	'stocknumber',
    	'quantity_delivered',
        'unit_cost'
    ];
    
    public function scopeFindByDeliveryID($query, $value)	{
		return $query->where('delivery_id', '=', $value);
    }

}
