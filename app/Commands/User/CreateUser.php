<?php

namespace App\Commands\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
    protected $request;
    
    public function __construct(Request $request)
    {
			$this->request = $request;
    }
 
    public function handle()
    {
			$request = $this->request;
			User::create([
				'lastname' => $request->lastname,
				'firstname' => $request->firstname,	
				'middlename' => $request->middlename,
				'username' => $request->username,
				'email' => $request->email,
				'access' => $request->access,
				'office' => $request->office,
				'position' => $request->position,
				'status' => 1,
				'password' => Hash::make($request->password),
			]);

			// create an alert stating that the tasks has
			// been completed successfully using the language
			// files
			\Alert::success(__('tasks.completed'))->flash();
    }
}