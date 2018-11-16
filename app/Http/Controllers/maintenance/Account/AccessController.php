<?php

namespace App\Http\Controllers\Account;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Commands\User\UpdateAccess;

class AccessController extends Controller 
{

	/**
	 * Updates access level of a user
	 *
	 * @return void
	 */
	public function update(Request $request, $id)	
	{
		$this->dispatch(new UpdateAccess($request, $id));
		return redirect('account');
	}
}
