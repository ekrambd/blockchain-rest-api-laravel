<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function Dashboard()
    {    
        return view('layouts.app');
    }
}
