<?php

namespace App\Commands\FundCluster;

use App\Models\FundCluster;

class CreateFundCluster
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
        // supplier table
        FundCluster::create([
            'code' => $request->code,
            'description' => $request->description,
        ]);

        // create an alert stating that a new supplier
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}