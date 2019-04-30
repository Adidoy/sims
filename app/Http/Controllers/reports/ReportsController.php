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
use App\Models\Reports\Summary\Summary;
use App\Models\Inventory\StockCards\StockCard;


class ReportsController extends Controller
{

    public function summaryIndex(Request $request)
    {
        $year =  StockCard::filterByYearIssued()->pluck('fiscalyear');
        if($request->ajax()) {
            $inputYear = $year[0];
            $endingBalance = Summary::endingBalance($inputYear)->get();
			return datatables($endingBalance)->toJson();
        }
        return view('reports.summary.summary_index')
            ->with('years', $year);
    }

    public function summaryPrint(Request $request)
    {
        $inputYear = $request->get("period");
        $endingBalance = Summary::endingBalance($inputYear)->get();
        $orientation = 'Portrait';
        $data = [
            'asof' => $inputYear,
            'endingBalance' => $endingBalance
        ];
        $filename = "ENDING INVENTORY AS OF ".$inputYear.".pdf";
        $view = "reports.summary.print_summary";
        return $this->printPreview($view,$data,$filename,$orientation);                    
    }

    public function getRecords(Request $request, $year)
    {
        if($request->ajax()) {
            $inputYear = $year;
            $endingBalance = Summary::endingBalance($inputYear)->get();
			return datatables($endingBalance)->toJson();
        }
    }
}