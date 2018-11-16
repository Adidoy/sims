<?php
namespace App;

use App\Models\Request\Request;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{

	protected $table = 'offices';
	protected $primaryKey = 'id';
	protected $fillable = [
		'code',
		'name',
		'description', 
		'head',
		'head_title',
		'head_office'
	];
	public $timestamps = true;

	public function rules(){
		return array(
			'Code' => 'required|max:20|unique:offices,code',
			'Name' => 'required|max:200',
			'Description' => 'max:200'
		);
	}

	public function updateRules(){
		$code = $this->code;
		return array(
			'Code' => 'required|max:20|unique:offices,code,'.$code.',code',
			'Name' => 'required|max:200',
			'Description' => 'max:200'
		);
	}

	/**
	 * Returns the head of the office
	 *
	 * @param string $value
	 * @return void
	 */
	public function getHeadAttribute($value)
	{
		if($value == null || $value == '' ) {
			return 'None';
		}

		return $value;
	}

	/**
	 * Filters the office by code
	 *
	 * @param Builder $query
	 * @param string $value
	 * @return object
	 */
	public function scopeCode($query, $value)
	{
		return $query->where('code', '=', $value)->first();
	}

	/**
	 * Links the department where is the head office
	 *
	 * @return object
	 */
	public function departments()
	{
		return $this->hasMany(Office::class, 'head_office', 'id');
	}

	/**
	 * Links to the request class
	 *
	 * @return object
	 */
	public function request()
	{
		return $this->hasMany(Request::class, 'office_id', 'id');
	}

	/**
	 * Links to the head office
	 *
	 * @return void
	 */
	public function headOffice()
	{
		return $this->belongsTo(Office::class, 'head_office', 'id');
	}
}
