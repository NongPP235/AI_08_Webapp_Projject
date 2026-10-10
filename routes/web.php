<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. หน้าสำหรับทุกคน (Guest เข้าได้)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/knowledge', [HomeController::class, 'knowledge'])->name('tax.knowledge');

// เครื่องคำนวณภาษี Guest ใช้ได้ แต่จะไม่มีการบันทึกประวัติ
Route::get('/tax-calculate', [TaxController::class, 'showForm'])->name('tax.show');
Route::post('/tax-calculate', [TaxController::class, 'calculateTax'])->name('tax.calculate');

/*
|--------------------------------------------------------------------------
| 2. หน้าสำหรับ User ที่ล็อกอินแล้ว
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/history', [UserDashboardController::class, 'history'])->name('history');

    // ออกจากระบบ
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| 3. หน้าสำหรับ Administrator (ต้องล็อกอิน และ role = admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // หน้าหลักจัดการระบบของ Admin (/admin/dashboard)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| 4. Login / Register (เฉพาะคนที่ยังไม่ได้ล็อกอิน)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});