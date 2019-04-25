<?php

namespace App\Http\Controllers\Reports;

use DB;
use Auth;
use Carbon;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\Inventory\StockCards\StockCard;

class ReportsController extends Controller
{

    public function summaryIndex()
    {
        $year =  StockCard::filterByYearIssued()->pluck('fiscalyear');
        return view('reports.summary.summary_index')
            ->with('years', $year);
    }

    public function summaryPrint(Request $request)
    {
        $inputYear = $request->get("years");
        $year = substr($inputYear,strlen($inputYear)-4,4);
        $month = substr($inputYear,0,strlen($inputYear)-5);
        $startDate = $year.'-01-01';
        $endDate = \Carbon\Carbon::parse($month." 01, ".$year);
        return $endingBalance = DB::table('stockcards')
                    ->join('supplies', 'stockcards.supply_id', '=', 'supplies.id')
                    ->select('supplies.stocknumber', 'supplies.details', DB::raw('(SUM(received_quantity) - SUM(issued_quantity)) "balance"'))
                    ->whereRaw("date BETWEEN '".$startDate."' AND LAST_DAY('".$endDate."')")
                    ->groupBy('supplies.stocknumber', 'supplies.details')
                    ->get();
    }

    public function getRecords($year)
    {
        //======================================================================================================================//
        //SQL Conversion of the desired output:                                                                                 //
        //                                                                                                                      //
        //SELECT supplies.`stocknumber`, supplies.`details`, (SUM(received_quantity) - SUM(issued_quantity)) 'balance'          //
        //FROM stockcards JOIN supplies ON stockcards.`supply_id` = supplies.`id`                                               //
        //WHERE stockcards.`date` BETWEEN '2018-01-01' AND '2018-12-31' - okay                                                  //
        //GROUP BY supplies.`stocknumber`, supplies.`details`                                                                   //
        //                                                                                                                      //
        //                                                                                                                      //
        //======================================================================================================================//

        return $endingBalance = DB::table('stockcards')
            ->join('supplies', 'stockcards.supply_id', '=', 'supplies.id')
            ->select('supplies.stocknumber', 'supplies.details', DB::raw('(SUM(received_quantity) - SUM(issued_quantity)) "balance"'))
            ->whereRaw("date BETWEEN '01-01-2018' AND LAST_DAY(NOW())")
            ->groupBy('supplies.stocknumber', 'supplies.details')
            ->get();
    }
}