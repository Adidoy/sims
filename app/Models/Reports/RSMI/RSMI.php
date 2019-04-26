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
        ->whereRaw("released_at BETWEEN '".$startDate."' AND '".$startDate->endOfMonth()."'")
        ->select('local',DB::raw("offices.id AS office"), 'stocknumber','details','units.name','requests_supplies.quantity_issued');
    }
}