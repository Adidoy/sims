<?php

namespace App\Http\Controllers\Account;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Commands\User\CreateUser;
use App\Commands\User\UpdateUser;
use App\Commands\User\RemoveUser;
use App\Http\Controllers\Controller;

class PasswordResetController extends Controller 
{

	/**
	 * Updates the password of the current user
	 * to the default password
	 *
	 * @param Request $request
	 * @return void
	 */
	public function reset(Request $request)	
	{

		$this->dispatch(new ResetPassword($request, $id));
		return redirect('account');
	}
}
