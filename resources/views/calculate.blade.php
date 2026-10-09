<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Calculate</title>

    <style>
        /* ===== Reset & Base ===== */
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #16a34a;
            --success-dark: #15803d;
            --bg: #f1f5f9;
            --card: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --border: #cbd5e1;
            --radius: 10px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Sarabun", "Noto Sans Thai", "Segoe UI", Tahoma, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== Header & Nav ===== */
        header {
            background: var(--primary);
            color: #fff;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        header h1 {
            font-size: 1.6rem;
        }

        nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px 18px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        nav span {
            font-weight: 600;
        }

        nav form button {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s;
        }

        nav form button:hover {
            background: #dc2626;
        }

        /* ===== Main ===== */
        main {
            flex: 1;
            width: 100%;
            max-width: 860px;
            margin: 28px auto;
            padding: 0 16px;
        }

        main > form,
        .net-income {
            background: var(--card);
            padding: 28px 32px;
            border-radius: var(--radius);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .net-income {
            margin-top: 24px;
        }

        h3 {
            color: var(--primary-dark);
            margin-bottom: 14px;
            padding-bottom: 6px;
            border-bottom: 2px solid var(--bg);
        }

        h4 {
            margin: 18px 0 10px;
            color: var(--muted);
            font-size: 1rem;
        }

        hr {
            border: none;
            border-top: 1px dashed var(--border);
            margin: 26px 0;
        }

        /* ===== Form Elements ===== */
        label {
            display: block;
            font-weight: 500;
            margin-bottom: 4px;
        }

        input[type="number"],
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="number"]:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        /* กลุ่มช่องกรอกแต่ละบรรทัด */
        .personal-status > *,
        .income > div,
        .deductions > div {
            margin-bottom: 14px;
        }

        .personal-status label {
            margin-top: 12px;
        }

        /* checkbox */
        label[for="is_disable"] {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            cursor: pointer;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        /* ช่องรายได้ ม.40(6) ที่มี select + input ในกลุ่มเดียว */
        .income_g6 select {
            margin-bottom: 10px;
        }

        /* ===== Buttons ===== */
        main form > button {
            margin-top: 10px;
            margin-right: 8px;
            padding: 12px 26px;
            border: none;
            border-radius: 8px;
            background: var(--primary);
            color: #fff;
            font-size: 1rem;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        main form > button:hover {
            background: var(--primary-dark);
        }

        main form > button:active {
            transform: scale(0.98);
        }

        /* ปุ่มบันทึก (ที่ใส่ inline style สีเขียวไว้) */
        main form > button[style*="green"] {
            background: var(--success) !important;
        }

        main form > button[style*="green"]:hover {
            background: var(--success-dark) !important;
        }

        /* ข้อความสำหรับ guest */
        .guest-alert {
            margin-top: 14px;
            padding: 12px 16px;
            background: #eff6ff;
            border-left: 4px solid var(--primary);
            border-radius: 6px;
        }

        .guest-alert a {
            color: var(--primary);
            font-weight: 600;
        }

        /* ===== Result Box ===== */
        .result-box {
            background: linear-gradient(135deg, #eff6ff, #f0fdf4);
            border: 1px solid #bfdbfe;
            border-radius: var(--radius);
            padding: 20px 24px;
        }

        .result-box p {
            font-size: 1.1rem;
            margin: 8px 0;
        }

        .result-box .amount {
            color: var(--primary-dark);
            font-size: 1.4rem;
            font-weight: 700;
        }

        .result-box .taxpay {
            color: #dc2626;
            font-size: 1.4rem;
            font-weight: 700;
        }

        /* ===== Footer ===== */
        footer {
            text-align: center;
            padding: 18px;
            background: #1e293b;
            color: #cbd5e1;
            font-size: 0.9rem;
        }

        /* ===== Responsive ===== */
        @media (max-width: 600px) {
            header {
                padding: 14px 16px;
                flex-direction: column;
                align-items: flex-start;
            }

            main > form,
            .net-income {
                padding: 20px 16px;
            }

            main form > button {
                width: 100%;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Personal Tax</h1>

        <nav>
            <a href="/">หน้าหลัก</a>
            <a href="/knowledge">ความรู้เกี่ยวกับภาษี</a>
            <a href="/about">เกี่ยวกับ</a>

            @guest
                <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
                <a href="{{ route('register') }}">สมัครสมาชิก</a>
            @endguest
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="/admin/dashboard">จัดการระบบ (Admin)</a>
                @endif
                <span>👤 {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">ออกจากระบบ</button>
                </form>
            @endauth
        </nav>

    </header>

    <main>
        <form action="{{ route('tax.calculate') }}" method="post">
            @csrf
            <h3>ข้อมูลส่วนตัวและสถานะ</h3>
            <div class="personal-status">
                <label for="married-status">1.สถานะภาพสมรส</label>

                <select name="married-status" id="married-status" required>
                    <option value="single">โสด</option>
                    <option value="married_no_income">สมรส(คู่สมรสไม่มีรายได้)</option>
                    <option value="married_has_income">สมรส(คู่สมรสมีรายได้ / แยกยื่นภาษีเอง)</option>
                </select>

                <label for="child_count">จำนวนบุตร : </label>
                <input type="number" name="child_count" id="child_count" min="0" value="0" >

                <label for="is_disable">
                    <input type="checkbox" name="is_disable" id="is_disable" value="false"> ผู้พิการ
                </label>
            </div>

            <hr>

            <div class="income">
                <h3>2. ข้อมูลเงินได้พึงประเมิน (รายได้ทั้งปี)</h3>

                <h4>กลุ่มรายได้จากแรงงานและงานประจำ</h4>
                <div class="income_g1">
                    <label for="income_g1">1.เงินเดือน โบนัส ค่าจ้างประจำ (ม.40(1)): </label>
                    <input type="number" name="income_g1" id="income_g1" min="0" value="0">
                </div>
                <div class="income_g2">
                    <label for="income_g2">2.ค่านายหน้า ฟรีแลนซ์ เบี้ยประชุม ค่ารีวิว (ม.40(2)):</label>
                    <input type="number" name="income_g2" id="income_g2" min="0" value="0">
                </div>
                <div class="income_g3">
                    <label for="">3.กลุ่มรายได้จากทรัพย์สิน สิทธิ์ และการลงทุน</label>
                    <input type="number" name="income_g3" id="income_g3" min="0" value="0">
                </div>
                <div class="income_g4">
                    <label for="">4.ดอกเบี้ย เงินปันผลจากหุ้น คริปโทเคอร์เรนซี (ม.40(4)):</label>
                    <input type="number" name="income_g4" id="income_g4" min="0" value="0">
                </div>
                <div class="income_g5">
                    <label for="">5.รายได้จากการให้เช่าบ้าน ที่ดิน คอนโด รถยนต์ (ม.40(5)):</label>
                    <input type="number" name="income_g5" id="income_g5" min="0" value="0">
                </div>

                <h4>กลุ่มรายได้จากวิชาชีพอิสระและธุรกิจ</h4>

                <div class="income_g6">
                    <label for="income_g6_type">6.ประเภทวิชาชีพอิสระ (ม.40(6)):</label>
                    <select name="income_g6_type" id="income_g6_type">
                        <option value="none">--ไม่มีรายได้ในส่วนนี้--</option>
                        <option value="medical">ประกอบโรคศิลปะ / แพทย์ (เหมา 60%)</option>
                        <option value="other">กฎหมาย บัญชี วิศวกรรม สถาปัตยกรรม ประณีตศิลปกรรม (เหมา 30%)</option>
                    </select>
                    <label for="income_g6">จำนวนเงินรายได้ ม.40(6):</label>
                    <input type="number" name="income_g6" id="income_g6" min="0" value="0">
                </div>
                <div class="income_g7">
                    <label for="">7.รายได้จากการรับเหมาก่อสร้าง (รวมค่าสัมภาระ) (ม.40(7)):</label>
                    <input type="number" name="income_g7" id="income_g7" min="0" value="0">
                </div>
                <div class="income_g8">
                    <label for="">8.รายได้จากการค้าขาย ธุรกิจออนไลน์ เกษตรกรรม ขายอสังหาฯ (ม.40(8)):</label>
                    <input type="number" name="income_g8" id="income_g8" min="0" value="0">
                </div>
            </div>
            <hr>
            <div class="deductions">
                <h3>3. ค่าลดหย่อน (บาทต่อปี)</h3>
                <div class="deduct_g1">
                    <label for="deduct_social">เงินสมทบประกันสังคม (สูงสุด 9,000 บาท):</label>
                    <input type="number" name="deduct_social" id="deduct_social" min="0" max="9000" value="0" required>
                </div>
                <div class="deduct_g2">
                    <label for="deduct_life">เบี้ยประกันชีวิตทั่วไป (สูงสุด 100,000 บาท):</label>
                    <input type="number" name="deduct_life" id="deduct_life" min="0" max="100000" value="0" required>
                </div>
                <div class="deduct_g3">
                    <label for="deduct_health">เบี้ยประกันสุขภาพตนเอง (สูงสุด 25,000 บาท):</label>
                    <input type="number" name="deduct_health" id="deduct_health" min="0" max="25000" value="0" required>
                </div>
                <div class="deduct_g4">
                    <label for="deduct_donation">เงินบริจาคทั่วไป (ลดหย่อนตามจริงไม่เกิน 10% ของเงินได้หลังหักค่าลดหย่อนอื่น):</label>
                    <input type="number" name="deduct_donation" id="deduct_donation" min="0" value="0" required>
                </div>
            </div>

            <button type="submit" name="action" value="save">คำนวณภาษี</button>
            
            @auth
                <button type="submit" name="action" value="save" style="background-color: green; color: white;">
                    บันทึกข้อมูลข้อนี้ไว้ดูย้อนหลัง
                </button>
            @endauth

            @guest
                <div class="guest-alert" style="margin-top: 10px; font-size: 14px; color: #555;">
                    ไม่อยากกรอกใหม่คราวหน้า? <a href="{{ route('register') }}">สมัครสมาชิก</a> เพื่อบันทึกข้อมูลภาษีของคุณได้ฟรี
                </div>
            @endguest
                
        </form>
        
        <div class="net-income">
            <h3>รายได้สุทธิทั้งหมด</h3>

            <!-- ผมต้องการโชว์ตัวเลขที่คำนวนตรงนี้ -->

            @if(isset($netIncome) && isset($taxPayable))
                <div class="result-box">
                    <p>รายได้สุทธิทั้งหมด: <span class="amount">{{ number_format($netIncome, 2) }}</span> บาท</p>
                    <p>ยอดชำระภาษีที่ต้องจ่าย: <span class="taxpay">{{ number_format($taxPayable, 2) }}</span> บาท</p>
                </div>
            @else
                <p>กรอกข้อมูลด้านบนแล้วกดปุ่มคำนวณภาษี</p>
            @endif

        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} TaxCalculator. All rights reserved.</p>
    </footer>
</body>
</html>