<?php

namespace App\Commands\Supply;

use App\Supply;

class UpdateSupply
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

		Supply::findOrFail($this->id)->update([
			'stocknumber' => $request->get('stocknumber'),
			'details' => $request->get('details'),
			'unit_id' => $request->get('unit'),
			'reorderpoint' => $request->get('reorderpoint'),
		]);

	}
}