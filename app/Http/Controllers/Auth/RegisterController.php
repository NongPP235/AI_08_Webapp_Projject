<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // กำหนดเส้นทางหลังจากสมัครสมาชิกเสร็จ (ให้ล็อกอินแล้วกระโดดไปหน้าคำนวณภาษี)
    protected $redirectTo = '/tax-calculate';

    /**
     * 1. ฟังก์ชันแสดงหน้าฟอร์ม สมัครสมาชิก
     */
    public function showRegistrationForm()
    {
        return view('auth.register'); // ดึงไฟล์ resources/views/auth/register.blade.php
    }

    /**
     * 2. ฟังก์ชันประมวลผลการสมัครสมาชิก และบันทึกลงฐานข้อมูล
     */
    public function register(Request $request)
    {
        // ตรวจสอบข้อมูลฟอร์มสมัครสมาชิก
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // บังคับรหัสผ่านยาว 8 ตัวขึ้นไป และตรงกับช่องยืนยันรหัส
        ], [
            'name.required'     => 'กรุณากรอกชื่อผู้ใช้งาน',
            'email.required'    => 'กรุณากรอกอีเมล',
            'email.email'       => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'      => 'อีเมลนี้ถูกใช้งานในระบบแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min'      => 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
            'password.confirmed'=> 'รหัสผ่านและช่องยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        // สร้างข้อมูลผู้ใช้ใหม่ลงตารางฐานข้อมูล (ตาราง users)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // เข้ารหัสลับรหัสผ่านก่อนบันทึกเพื่อความปลอดภัย
            'role'     => 'user',                         // กำหนดสิทธิ์เริ่มต้นเป็น user เสมอ (ป้องกันผู้ใช้สมัครสิทธิ์เป็น admin เอง)
        ]);

        // สั่งให้ระบบล็อกอินเข้าสู่ระบบให้กับผู้ใช้ใหม่คนนี้ทันที
        Auth::login($user);

        // ส่งตัวผู้ใช้ไปยังหน้าที่ตั้งค่าไว้
        return redirect($this->redirectTo);
    }
}
