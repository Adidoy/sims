<?php

namespace App\Http\Controllers;

use App\Models\Audit\Audit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $audits = Audit::all();
            return datatables($audits)->toJson();
        }

        return view('audit.index');
    }
}
