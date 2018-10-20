<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveriesDetail extends Model
{
    protected $table = 'deliveries_details';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'supply_id',
    	'quantity',
        'first_inspect',
        'first_inspect_date',
        'first_inspect_remarks',
        'second_inspect',
        'second_inspect_date',
        'second_inspect_remarks',
        'director_approval',
        'director_approval_date',
    	'director_approval_remarks'
    ];
    
    

}
