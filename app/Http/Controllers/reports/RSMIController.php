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
use App\Models\Requests\Custodian\RequestCustodian;

class RSMIController extends Controller
{
    public function index(Request $request)
    {
        $year =  RequestCustodian::filterByRequestPeriod()->pluck('fiscalyear');
        $rsmiItems = RSMI::rsmiitems($year[0])->get();
        $rsmiItems = json_decode($rsmiItems);
        for($i = 0; $i < count($rsmiItems); $i++) {
            $rsmiItems[$i]->office = App\Models\Sector::findSectorCode($rsmiItems[$i]->office) .' - '. App\Office::find($rsmiItems[$i]->office)->name;
        }
        if($request->ajax()) {
            return datatables($rsmiItems)->toJson();
        }
        return view('reports.rsmi.index')
            ->with('years', $year);
    }

    public function getRecords(Request $request, $period)
    {
        $rsmiItems = RSMI::rsmiitems($period)->get();
        $rsmiItems = json_decode($rsmiItems);
        for($i = 0; $i < count($rsmiItems); $i++) {
            $rsmiItems[$i]->office = App\Models\Sector::findSectorCode($rsmiItems[$i]->office) .' - '. App\Office::find($rsmiItems[$i]->office)->name;
        }
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