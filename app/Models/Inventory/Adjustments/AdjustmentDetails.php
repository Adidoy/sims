<?php

namespace App\Models\Inventory\Adjustments;

use DB;
use Auth;
use Carbon;
use App\Supply;
use Illuminate\Database\Eloquent\Model;

class AdjustmentDetails extends Model
{
    protected $table = 'adjustment_details';
    //protected $primaryKey = 'id';
    //public $incrementing = true;
    public $timestamps = true;
    protected $fillable = ['adjustment_id', 'supply_id', 'quantity', 'unit_cost', 'total_cost'];
}
