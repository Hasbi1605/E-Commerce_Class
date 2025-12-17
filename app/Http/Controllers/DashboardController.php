<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = array('title' => 'Dashboard');
        // the layout view is stored in resources/views/layouts/dashboard.blade.php
        // the layout view is stored in resources/views/layouts/dashboard.blade.php
        return view('layouts.dashboard', $data);
    }
}
