<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการระบบ - TaxCalculator</title>
</head>
<body>

    <header>
        <h1>Personal Tax</h1>
        <nav>
            <a href="/">หน้าหลัก</a>
            <a href="/knowledge">ความรู้เกี่ยวกับภาษี</a>
            <a href="/about">เกี่ยวกับ</a>
            <span>👤 {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit">ออกจากระบบ</button>
            </form>
        </nav>
    </header>

    <main>
        <h2>แผงควบคุมผู้ดูแลระบบ (Admin Dashboard)</h2>
        <p>หน้านี้เข้าได้เฉพาะผู้ใช้ที่มีสิทธิ์ admin เท่านั้น</p>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} TaxCalculator. All rights reserved.</p>
    </footer>

</body>
</html>