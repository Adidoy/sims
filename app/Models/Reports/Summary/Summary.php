<?php

namespace App\Models\Reports\Summary;

use DB;
use Auth;
use Event;
use Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\UserResolver;


class Summary extends Model
{
    public static function EndingBalance($inputYear)
    {
        $year = substr($inputYear,strlen($inputYear)-4,4);
        $month = substr($inputYear,0,strlen($inputYear)-5);
        $startDate = '2018-01-01';
        $endDate = \Carbon\Carbon::parse($month." 01, ".$year);
        return $endingBalance = DB::table('stockcards')
            ->rightJoin('supplies', 'stockcards.supply_id', '=', 'supplies.id')
            ->select('supplies.stocknumber', 'supplies.details', DB::raw('(SUM(received_quantity) - SUM(issued_quantity)) "balance"'))
            ->whereRaw("date BETWEEN '".$startDate."' AND LAST_DAY('".$endDate."')")
            ->groupBy('supplies.stocknumber', 'supplies.details');
    }
}