<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaxController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'index']);
Route::get('/about',[HomeController::class,'about']);
Route::get('/knowledge',[HomeController::class,'knowledge']);

Route::get('/tax-calculate',[TaxController::class,'showForm']) -> name('tax.show');
Route::post('/tax-calculate',[TaxController::class,'calculateTax']) -> name('tax.calculate');