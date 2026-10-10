<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/knowledge', [HomeController::class, 'knowledge'])->name('tax.knowledge');

Route::get('/tax-calculate', [TaxController::class, 'showForm'])->name('tax.show');
Route::post('/tax-calculate', [TaxController::class, 'calculateTax'])->name('tax.calculate');




Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/history',[UserDashboardController::class,'history'])->name('history');
    });


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // หน้าหลักจัดการระบบของ Admin (/admin/dashboard)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

});

/*
|--------------------------------------------------------------------------
| 4. ระบบการพิสูจน์ตัวตนพื้นฐาน (Laravel Auth Routes)
|--------------------------------------------------------------------------
| บรรทัดนี้จำเป็นต้องมี เพื่อดึงไฟล์ระบบล็อกอิน/สมัครสมาชิก (login, register, logout)
| ที่สร้างจาก Laravel Breeze หรือ Auth Scaffold เข้ามาร่วมใช้งานในโปรเจกต์
*/

/*
|--------------------------------------------------------------------------
| 5. ระบบ Custom Auth Routes (ใช้ร่วมกับ LoginController และ RegisterController)
|--------------------------------------------------------------------------
*/

// กลุ่มสำหรับ Guest (คนที่ยังไม่ได้ล็อกอิน) ถึงจะเข้าหน้าพวกนี้ได้
Route::middleware('guest')->group(function () {
    
    // เส้นทางสำหรับระบบ Login (เข้าสู่ระบบ)
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // เส้นทางสำหรับระบบ Register (สมัครสมาชิก)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// กลุ่มสำหรับคนที่ล็อกอินแล้ว ถึงจะสั่งออกจากระบบได้
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});