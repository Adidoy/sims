<?php

namespace App\Commands\Office;

use App\Models\Office\Office;

class RemoveOffice
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

        // removes the copy of the office
        Office::findOrFail($this->id)->delete();

        // create an alert stating that a new office
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}