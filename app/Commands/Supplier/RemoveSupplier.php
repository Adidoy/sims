<?php

namespace App\Commands\Supplier;

use App\Models\Supply\Supplier;

class RemoveSupplier
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

        // removes the copy of the supplier
        Supplier::findOrFail($this->id)->delete();

        // create an alert stating that a new supplier
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}