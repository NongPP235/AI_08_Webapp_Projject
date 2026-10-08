<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'index']);
Route::get('/about',[HomeController::class,'about']);
Route::get('/knowledge',[HomeController::class,'knowledge']);