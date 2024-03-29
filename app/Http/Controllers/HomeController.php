<?php

namespace App\Http\Controllers;

use Auth;
use App\Services\Dashboard;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Dashboard $dashboard)
    {   
        return $dashboard->showDashboard($request);
    }
}
