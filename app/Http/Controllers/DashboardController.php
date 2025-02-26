<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function home()
    {
        return view('dashboard');
    }
}
