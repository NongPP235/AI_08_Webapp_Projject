<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // กำหนดเส้นทางหลังจากล็อกอินสำเร็จ (ให้กระโดดไปหน้าคำนวณภาษี)
    protected $redirectTo = '/tax-calculate';

    /**
     * 1. ฟังก์ชันแสดงหน้าฟอร์ม Login
     */
    public function showLoginForm()
    {
        return view('auth.login'); // ดึงไฟล์ resources/views/auth/login.blade.php
    }

    /**
     * 2. ฟังก์ชันประมวลผลการ Login
     */
    public function login(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูลที่ส่งมา
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'กรุณากรอกอีเมล',
            'email.email'       => 'รูปแบบอีเมลไม่ถูกต้อง',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        // จำค่า Remember Me (ถ้าผู้ใช้กดติ๊กถูกมา)
        $remember = $request->has('remember');

        // พยายามตรวจสอบรหัสผ่านและล็อกอินเข้าสู่ระบบ
        if (Auth::attempt($credentials, $remember)) {
            // สร้าง Session ID ใหม่เพื่อความปลอดภัย
            $request->session()->regenerate();

            // เช็กสิทธิ์การใช้งาน (Role) เพื่อส่งไปหน้าแดชบอร์ดที่ถูกต้อง
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended($this->redirectTo);
        }

        // กรณีล็อกอินไม่สำเร็จ ให้ส่งข้อความแจ้งเตือนกลับไปที่หน้าเดิม
        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง',
        ])->withInput($request->only('email'));
    }

    /**
     * 3. ฟังก์ชันออกจากระบบ (Logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // ล้างข้อมูล Session ทั้งหมด
        $request->session()->invalidate();
        // สร้าง Token ใหม่เพื่อป้องกันการโจมตีซ้ำ
        $request->session()->regenerateToken();

        // ออกจากระบบเสร็จแล้วให้เด้งไปหน้าแรกของเว็บไซต์
        return redirect('/');
    }
}
