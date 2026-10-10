<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - TaxCalculator</title>
</head>
<body>
    
    <header>
        <h1>Personal Tax</h1>
        <nav>
            <a href="/">หน้าหลัก</a>
            <a href="/knowledge">ความรู้เกี่ยวกับภาษี</a>
            <a href="/about">เกี่ยวกับ</a>
            <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
        </nav>
    </header>

    <main>
        <section class="auth-container" style="max-width: 400px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
            <h2>สมัครสมาชิก</h2>
            <p>เริ่มต้นสร้างบัญชีเพื่อใช้ระบบบันทึกประวัติภาษีฟรี</p>

            @if ($errors->any())
                <div class="alert-danger" style="color: red; margin-bottom: 15px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="name" style="display: block; margin-bottom: 5px;">ชื่อผู้ใช้งาน (Name):</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus style="width: 100%; padding: 8px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">อีเมล (Email):</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="password" style="display: block; margin-bottom: 5px;">รหัสผ่าน (Password):</label>
                    <input type="password" name="password" id="password" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="password_confirmation" style="display: block; margin-bottom: 5px;">ยืนยันรหัสผ่าน (Confirm Password):</label>
                    <!-- 🚨 สำคัญ: ช่องนี้ใน Laravel บังคับตั้งชื่อ name="password_confirmation" เท่านั้น -->
                    <input type="password" name="password_confirmation" id="password_confirmation" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                </div>

                <button type="submit" style="background-color: green; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%;">
                    ยืนยันการสมัครสมาชิก
                </button>
            </form>

            <div style="margin-top: 15px; text-align: center; font-size: 14px;">
                มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}">เข้าสู่ระบบที่นี่</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} TaxCalculator. All rights reserved.</p>
    </footer>

</body>
</html>
