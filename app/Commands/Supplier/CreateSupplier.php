<?php

namespace App\Commands\Supplier;

use App\Models\Supply\Supplier;

class CreateSupplier
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
        Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'email' => $request->email,
            'website' => $request->website,
        ]);

        // create an alert stating that a new supplier
        // has been created
		\Alert::success(__('tasks.completed'))->flash();

	}
}