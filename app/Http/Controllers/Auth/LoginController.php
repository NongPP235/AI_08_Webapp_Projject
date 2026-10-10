<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    protected $redirectTo = '/tax-calculate';

    public function showLoginForm()
    {
        return view('auth.login'); 
    }

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

        // สร้างคีย์สำหรับนับจำนวนครั้งที่ลอง (แยกตามอีเมล + IP ของผู้ใช้)
        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => 'คุณลองเข้าสู่ระบบผิดหลายครั้งเกินไป กรุณารออีก ' . $seconds . ' วินาทีแล้วลองใหม่',
            ])->withInput($request->only('email'));
        }

        // จำค่า Remember Me (ถ้าผู้ใช้กดติ๊กถูกมา)
        $remember = $request->has('remember');

        // พยายามตรวจสอบรหัสผ่านและล็อกอินเข้าสู่ระบบ
        if (Auth::attempt($credentials, $remember)) {
            // ล็อกอินสำเร็จ ล้างตัวนับที่ลองผิดไว้
            RateLimiter::clear($throttleKey);

            // สร้าง Session ID ใหม่เพื่อความปลอดภัย
            $request->session()->regenerate();

            // เช็กสิทธิ์การใช้งาน (Role) เพื่อส่งไปหน้าแดชบอร์ดที่ถูกต้อง
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended($this->redirectTo);
        }

        // ล็อกอินไม่สำเร็จ นับเพิ่ม 1 ครั้ง (ตัวนับหมดอายุใน 60 วินาที)
        RateLimiter::hit($throttleKey, 60);

        // ส่งข้อความแจ้งเตือนกลับไปที่หน้าเดิม
        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง',
        ])->withInput($request->only('email'));
    }

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