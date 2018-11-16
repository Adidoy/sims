<?php

namespace App\Commands\Unit;

use App\Models\Supply\Unit;

class RemoveUnit
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
        Unit::findOrFail($this->id)->delete();

        // create an alert stating that a new category
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}