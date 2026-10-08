<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('index');
    }

    public function knowledge(){
        return view('knowledge');
    }

    public function about(){
        return view('about');
    }

    public function tax_calculate() {
        return view('calculate');
    }
}
