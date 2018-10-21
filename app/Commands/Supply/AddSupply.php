<?php

namespace App\Commands\Supply;

use App\Supply;

class AddSupply
{
	protected $request;

	public function __construct($request)
	{
		$this->request = $request;
	}

	public function handle()
	{
		$request = $this->request;
		Supply::create([
			'stocknumber' => $request->get('stocknumber'),
			'details' => $request->get('details'),
			'unit_id' => $request->get('unit'),
			'reorderpoint' => $request->get('reorderpoint'),
		]);

	}
}