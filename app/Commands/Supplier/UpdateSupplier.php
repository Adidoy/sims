<?php

namespace App\Commands\Supplier;

use App\Models\Supply\Supplier;

class UpdateSupplier
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
        Supplier::findOrFail($this->id)->update([
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