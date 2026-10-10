<?php

namespace App\Http\Controllers;

class UserDashboardController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }

    public function history()
    {
        return view('user.history');
    }
}