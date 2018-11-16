<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundCluster extends Model
{
    protected $table = 'fundclusters';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [ 
        'code', 'description' 
    ];

    /**
     * Filters fund cluster by code
     *
     * @param Builder $query
     * @param string $value
     * @return object
     */
    public function scopeCode($query, $value)
    {
    	return $query->where('code', '=', $value);
    }

    /**
     * Links to the purchase order class
     *
     * @return object
     */
	public function purchaseorders()
	{
		return $this->belongsToMany(PurchaseOrder::class, 'purchaseorders_fundclusters', 'fundcluster_id', 'purchaseorder_id')
          ->withTimestamps();
	}
}
