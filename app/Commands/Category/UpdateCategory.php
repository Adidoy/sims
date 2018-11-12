<?php

namespace App\Commands\Category;

use App\Models\Supply\Category;

class UpdateCategory
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
        Category::findOrFail($this->id)->update([
            'name' => $request->name,
            'uacs_code' => $request->code,
        ]);

        // create an alert stating that a new category
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}