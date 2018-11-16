<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
  	protected $table = 'suppliers';
	protected $primaryKey = 'id';
	public $incrementing = true;
	public $timestamps = true;
	protected $fillable = ['name', 'address', 'contact', 'website', 'email'];

	// public function purchaseorder()
	// {
	// 	return $this->hasMany('App\PurchaseOrder','supplier_id','id');
	// }
	
    // public function scopeFindBySupplierName($query, $value)	{
	// 	return $query->where('name', '=', $value);
	// }
}
