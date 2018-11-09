<?php

namespace App\Commands\Category;

use App\Models\Supply\Category;

class CreateCategory
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
        // categories table
        Category::create([
            'name' => $request->name,
            'uacs_code' => $request->code,
        ]);

        // create an alert stating that a new category
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}