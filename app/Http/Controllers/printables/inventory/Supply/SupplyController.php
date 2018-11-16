<?php

namespace App\Http\Controllers\printables\inventory\Supply;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Supply\Supply;
use App\Http\PrintWrapper\Printer;
use App\Http\Controllers\Controller;

class SupplyController extends Controller
{

    /**
     * Prints the supplies information using the following
     * data as value
     *
     * @return void
     */
	public function print()
	{
        $filename = "StockMasterlist-" . Carbon::now()->format('mdYHm') . ".pdf";
        
        return Printer::make('maintenance.supply.print_index', array(
            'supplies' => Supply::all()
        ))->print($filename);
	}
}
