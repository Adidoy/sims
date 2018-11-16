<?php

namespace App\Commands\User;

use App\Models\User\User;
use Illuminate\Http\Request;

class UpdateAccess
{
    protected $request;
    protected $id;

	public function __construct($request, $id)
	{
        $this->request = $request;
        $this->id = $id;
	}

	public function handle()
	{
		$request = $this->request;
		
        // create a new record in the
        // categories table
        User::findOrFail($this->id)->update([
			'access' => $request->access
        ]);

        // create an alert stating that a new category
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}