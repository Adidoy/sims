<?php

namespace App\Commands\Office;

use App\Models\Office\Office;

class CreateOffice
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
        // office table
        Office::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'head' => $request->head,
            'head_title' => $request->head_title,
        ]);

        // create an alert stating that a new office
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}