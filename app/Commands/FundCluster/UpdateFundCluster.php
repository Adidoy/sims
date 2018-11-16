<?php

namespace App\Commands\FundCluster;

use App\Models\FundCluster;

class UpdateFundCluster
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
        // suppliers table
        FundCluster::findOrFail($this->id)->update([
            'code' => $request->code,
            'description' => $request->description,
        ]);

        // create an alert stating that a new supplier
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}