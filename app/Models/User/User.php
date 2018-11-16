<?php

namespace App\Models\User\User;

use Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\UserResolver;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;


class User extends Model implements Authenticatable, Auditable, UserResolver
{
	use AuthenticableTrait;
	use \OwenIt\Auditing\Auditable;
	use Notifiable;

	protected $table  = 'users';
	protected $primaryKey = 'id';
	public $timestamps = true;
	protected $hidden = ['password', 'remember_token'];
	protected $fillable = [
		'lastname', 'firstname', 'middlename', 'username', 'password', 'email', 'status', 'access', 'position' 
	];

	/**
	 * Additional columns when fetching data from database
	 *
	 * @var array
	 */
	protected $appends = [
		'access_name', 'fullname'
	];

	/**
	 * Different access level of the user
	 * in the system
	 *
	 * @var array
	 */
	public static $access_list = [
		0 => "Administrator",
		1 => "PSMO-Director",
		2 => "Accounting",
		3 => "Offices",  
		4 => "Chief-Supplies",
		5 => "Director",
		6 => "PSMO-Releasing",
		7 => "PSMO-Accepting",
		8 => "PSMO-Disposal",
		9 => "Inspection Chief",
		10 => "Inspection Team"
	];

	/**
	 * Returns the full name attribute of the current 
	 * instantiated user. The fullname is the first and
	 * the last name of the user
	 *
	 * @return void
	 */
	public function getFullnameAttribute()
	{
		return $this->firstname . " " . $this->lastname;
	}

	/**
	 * Returns the equivalent name for the access of the
	 * current user
	 *
	 * @param int $value
	 * @return void
	 */
	public function getAccessNameAttribute(int $value)
	{
		return isset(self::$access_list[ $this->access ]) ? self::$access_list[ $this->access ] : 'Not Applicable';
	}


	// public function getRememberToken()
	// {
	// 	return null; // not supported
	// }

	// public function setRememberToken($value)
	// {
	// 	// not supported
	// }

	// public function getRememberTokenName()
	// {
	// 	return null; // not supported
	// }

	/**
	* Overrides the method to ignore the remember token.
	*/
	// public function setAttribute($key, $value)
	// {
	// 	$isRememberTokenAttribute = $key == $this->getRememberTokenName();
	// 	if (!$isRememberTokenAttribute)
	// 	{
	// 	 parent::setAttribute($key, $value);
	// 	}
	// }

	/**
	* {@inheritdoc}
	*/
	// public static function resolveId()
	// {
	// 	return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
	// }

	// public function scopeUserExists($query, $email, $username) {
	// 	return $query -> where('email', $email)
	// 		->where('username', $username)
	// 		->exists();
	// }

	// public function officeInfo()
	// {
	// 	return $this->belongsTo('App\Office','office','code');
	// }

	// public function comments()
    // {
    //     return $this->hasMany('App\RequestComments');
    // }

    // public function scopeFindByUserName($query, $value)
    // {
    // 	$query->where('username', '=', $value);
	// }

	// public function scopeFindByEmail($query, $value)
    // {
    // 	$query->where('email', '=', $value);
	// }

}
