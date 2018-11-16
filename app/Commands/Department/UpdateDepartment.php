<?php

namespace App\Commands\Department;

use App\Models\Office\Office;

class UpdateDepartment
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
        // offices table
        Office::findOrFail($this->id)->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'head' => $request->head,
            'head_title' => $request->designation,
        ]);

        // create an alert stating that a new office
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}