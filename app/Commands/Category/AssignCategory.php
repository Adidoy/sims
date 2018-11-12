<?php

namespace App\Commands\Category;

use App\Models\Supply\Category;

class AssignCategory
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

        // initialize the transaction of
        // database
        DB::beginTransaction();

        // sets the category id of the supplies with
        // the category filtered to null
        Supply::category($this->id)->update([
            'category_id' => null
        ]);

        // sets the value of the supply with the stock number 
        // to the category sent
        Supply::stockNumbers($request->stocknumber)->update([
            'category_id' => $id
        ]);
        
        // commit all the changes to the database
		DB::commit();

        // create an alert stating that the tasks has
        // been completed successfully using the language
        // files
		\Alert::success(__('tasks.completed'))->flash();

	}
}