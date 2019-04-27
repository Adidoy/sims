<?php

namespace App\Http\Controllers\Reports;

use DB;
use App;
use Auth;
use Carbon;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Models\Reports\RSMI\RSMI;
use App\Http\Controllers\Controller;
use App\Models\Inventory\StockCards\StockCard;

class RSMIController extends Controller
{
    public function index(Request $request)
    {
        $year =  StockCard::filterByYearIssued()->pluck('fiscalyear');
        $rsmiItems = RSMI::rsmiitems($year[0])->get();
        if($request->ajax()) {
            return datatables($rsmiItems)->toJson();
        }
        return view('reports.rsmi.index')
            ->with('years', $year);
    }

    public function getRecords(Request $request, $period)
    {
        $rsmiItems = RSMI::rsmiitems($period)->get();
        if($request->ajax()) {
            return datatables($rsmiItems)->toJson();
        }
    }

    public function print(Request $request)
    {
        $period = $request->get("period");
        $rsmiItems = RSMI::rsmiitems($period)->get();
        $rsmiRecap = RSMI::rsmirecap($period)->get();
        $rsmiRequest = RSMI::rsmiRequests($period)->get();
        // return $rsmiRecap;
        $orientation = 'Portrait';
        $data = [
            'asof' => $period,
            'rsmi' => $rsmiItems,
            'recap' => $rsmiRecap,
            'request' => $rsmiRequest
        ];
        $filename = "RSMI-".$period.".pdf";
        $view = "reports.rsmi.print_rsmi";
        return $this->printPreview($view,$data,$filename,$orientation);  
    }
}