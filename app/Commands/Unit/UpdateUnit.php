<?php

namespace App\Commands\Unit;

use App\Models\Supply\Unit;

class UpdateUnit
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
        // units table
        Unit::findOrFail($this->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'abbreviation' => $request->abbreviation,
        ]);

        // create an alert stating that a new unit
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}