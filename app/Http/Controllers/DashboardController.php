<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){

        $pageData = (new DashboardService())->handleDashboardLandingPage();

        return view('dashboard',compact('pageData'));
    }
}
