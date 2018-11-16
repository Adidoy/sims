<?php

namespace App\Commands\Unit;

use App\Models\Supply\Unit;

class CreateUnit
{
	protected $request;

	public function __construct($request)
	{
		$this->request = $request;
	}

	public function handle()
	{
        $request = $this->request;

        // create a new record in the
        // unit table
        Unit::create([
            'name' => $request->name,
            'description' => $request->description,
            'abbreviation' => $request->abbreviation,
        ]);

        // create an alert stating that a new unit
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}