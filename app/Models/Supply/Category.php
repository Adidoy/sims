<?php

namespace App\Models\Supply;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $table = 'categories';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = ['code', 'name', 'uacs_code'];
	protected $output = [];

	/**
	 * Filters the query by uacs code 
	 *
	 * @param Builder $query
	 * @param string $value
	 * @return object
	 */
	public function scopeFindByCode($query, $value)
	{
		return $query->where('uacs_code', '=', $value)->first();
	}

	/**
	 * Filters the query by uacs code 
	 *
	 * @param Builder $query
	 * @param string $value
	 * @return object
	 */
	public function scopeCode($query, $value)
	{
		return $query->where('uacs_code', '=', $value);
	}

	/**
	 * Filters the query by code 
	 *
	 * @param Builder $query
	 * @param string $value
	 * @return object
	 */
	public function scopeCodeLike($query, $value)
	{
		return $query->where('code', 'like', "%{$code}%");
	}

	/**
	 * Assigns the current class to the 
	 * initialized variable
	 *
	 * @param variable $variable
	 * @return object
	 */
	public function assignTo($variable)
	{
		$this->output = [ $variable => $this ];
		return $this;
	}

	/**
	 * Parsed the output to the json format
	 *
	 * @return void
	 */
	public function json()
	{
		return json_encode($this->output);
	}

}
