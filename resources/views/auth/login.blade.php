<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - TaxCalculator</title>
</head>
<body>
    
    <header>
        <h1>Personal Tax</h1>
        <nav>
            <a href="/">หน้าหลัก</a>
            <a href="/knowledge">ความรู้เกี่ยวกับภาษี</a>
            <a href="/about">เกี่ยวกับ</a>
            <!-- แสดงปุ่มสมัครสมาชิกเมื่ออยู่หน้าล็อกอิน -->
            <a href="{{ route('register') }}">สมัครสมาชิก</a>
        </nav>
    </header>

    <main>
        <section class="auth-container" style="max-width: 400px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
            <h2>เข้าสู่ระบบ</h2>
            <p>เข้าสู่ระบบเพื่อบันทึกและดูประวัติการคำนวณภาษีย้อนหลังของคุณ</p>

            <!-- แสดงข้อความแจ้งเตือนข้อผิดพลาด (เช่น กรอกรหัสผิด) -->
            @if ($errors->any())
                <div class="alert-danger" style="color: red; margin-bottom: 15px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">อีเมล (Email):</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus style="width: 100%; padding: 8px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="password" style="display: block; margin-bottom: 5px;">รหัสผ่าน (Password):</label>
                    <input type="password" name="password" id="password" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="remember_me">
                        <input type="checkbox" name="remember" id="remember_me"> จดจำฉันในระบบ
                    </label>
                </div>

                <button type="submit" style="background-color: blue; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%;">
                    เข้าสู่ระบบ
                </button>
            </form>

            <div style="margin-top: 15px; text-align: center; font-size: 14px;">
                ยังไม่มีบัญชีผู้ใช้? <a href="{{ route('register') }}">สมัครสมาชิกที่นี่</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} TaxCalculator. All rights reserved.</p>
    </footer>

</body>
</html>
