<?php

namespace App\Models\Reports\RSMI;

use DB;
use Auth;
use Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\StockCards\StockCard;

class RSMI extends Model
{
    protected $table = "rsmi";
    protected $primary = 'id';

    public $status_list = [
        'P' => 'Pending',
        'S' => 'Submitted',
        'R' => 'Received',
        'E' => 'Returned',
        'C' => 'Cancelled',
        'AP' => 'Applied To Ledger Card'    
    ];

    public function scopeRSMIItems($query, $period)
    {
      $year = substr($period,strlen($period)-4,4);
      $month = substr($period,0,strlen($period)-5);
      $startDate = \Carbon\Carbon::parse($month." 01, ".$year);
      return DB::table('requests')->join('offices', 'offices.id', '=', 'requests.office_id')
        ->join('requests_supplies', 'requests.id', '=', 'requests_supplies.request_id')
        ->join('supplies', 'supplies.id', '=', 'requests_supplies.supply_id')
        ->join('units', 'units.id', '=', 'supplies.unit_id')
        ->where('requests.status','released')
        ->where('requests_supplies.quantity_issued', '>' ,0)
        ->whereRaw("released_at BETWEEN '".$startDate."' AND '".$startDate->endOfMonth()."'")
        ->select('local',DB::raw("offices.id AS office"), 'stocknumber','details','units.name','requests_supplies.quantity_issued');
    }

    public function scopeRSMIRecap($query, $period)
    {
      $year = substr($period,strlen($period)-4,4);
      $month = substr($period,0,strlen($period)-5);
      $startDate = \Carbon\Carbon::parse($month." 01, ".$year);
      return DB::table('requests')->join('offices', 'offices.id', '=', 'requests.office_id')
        ->join('requests_supplies', 'requests.id', '=', 'requests_supplies.request_id')
        ->join('supplies', 'supplies.id', '=', 'requests_supplies.supply_id')
        ->join('units', 'units.id', '=', 'supplies.unit_id')
        ->where('requests.status','released')
        ->whereRaw("released_at BETWEEN '".$startDate."' AND '".$startDate->endOfMonth()."'")
        ->select('stocknumber','details','units.name',DB::raw("SUM(requests_supplies.quantity_issued) AS quantity_issued"))
        ->groupBy('stocknumber', 'details', 'units.name')
        ->having(DB::raw("SUM(requests_supplies.quantity_issued)"), '>', 0);
    }
    
    public function scopeRSMIRequests($query, $period)
    {
      $year = substr($period,strlen($period)-4,4);
      $month = substr($period,0,strlen($period)-5);
      $startDate = \Carbon\Carbon::parse($month." 01, ".$year);
      return DB::table('requests')->join('offices', 'offices.id', '=', 'requests.office_id')
        ->whereRaw("requests.updated_at BETWEEN '".$startDate."' AND '".$startDate->endOfMonth()."'")
        ->select('local',DB::raw("offices.id AS office"),'requests.status',DB::raw('DATE_FORMAT(requests.updated_at, "%d %b %Y") AS updated_at'),'requests.remarks');
    } 
}