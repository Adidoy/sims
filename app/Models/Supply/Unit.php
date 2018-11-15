<?php
namespace App\Models\Supply;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
	protected $table = 'units';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = [
		'name','description', 'abbreviation'
	];
}
