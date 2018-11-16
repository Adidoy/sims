<?php

namespace App\Commands\User;

use App\Models\User\User;
use Illuminate\Http\Request;

class RemoveUser
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

        // removes the copy of the category
        User::findOrFail($this->id)->delete();

        // create an alert stating that a new category
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}