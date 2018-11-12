<?php

namespace App\Commands\Category;

use App\Models\Supply\Category;

class RemoveCategory
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
        Category::findOrFail($this->id)->delete();

        // create an alert stating that a new category
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}