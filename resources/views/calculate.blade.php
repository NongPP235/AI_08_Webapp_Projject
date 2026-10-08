<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Calculate</title>
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
        <form action="">
            <h3>ข้อมูลส่วนตัวและสถานะ</h3>
            <div class="personal-status">
                <label for="married-status">1.สถานะภาพสมรส</label>

                <select name="married-status" id="married-status" required>
                    <option value="single">โสด</option>
                    <option value="married_no_income">สมรส(คู่สมรสไม่มีรายได้)</option>
                    <option value="married_has_income">สมรส(คู่สมรสมีรายได้ / แยกยื่นภาษีเอง)</option>
                </select>

                <label for="child_count">จำนวนบุตร : </label>
                <input type="number" name="child_count" id="child_count" min="0" value="0">

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
                    <input type="number" name="income_g6" id="income_g6">
                </div>
                <div class="income_g7">
                    <label for="">7.รายได้จากการรับเหมาก่อสร้าง (รวมค่าสัมภาระ) (ม.40(7)):</label>
                    <input type="number" name="income_g7" id="income_g7">
                </div>
                <div class="income_g8">
                    <label for="">8.รายได้จากการค้าขาย ธุรกิจออนไลน์ เกษตรกรรม ขายอสังหาฯ (ม.40(8)):</label>
                    <input type="number" name="income_g8" id="income_g8">
                </div>
            </div>
            <hr>
            <div class="deductions">
                <h3>3. ค่าลดหย่อน (บาทต่อปี)</h3>
                <div class="deduct_g1">
                    <label for="deduct_social">เงินสมทบประกันสังคม (สูงสุด 9,000 บาท):</label>
                    <input type="number" name="deduct_social" id="deduct_social" min="0" max="9000" value="0">
                </div>
                <div class="deduct_g2">
                    <label for="deduct_life">เบี้ยประกันชีวิตทั่วไป (สูงสุด 100,000 บาท):</label>
                    <input type="number" name="deduct_life" id="deduct_life" min="0" max="100000" value="0">
                </div>
                <div class="deduct_g3">
                    <label for="deduct_health">เบี้ยประกันสุขภาพตนเอง (สูงสุด 25,000 บาท):</label>
                    <input type="number" name="deduct_health" id="deduct_health" min="0" max="25000" value="0">
                </div>
                <div class="deduct_g4">
                    <label for="deduct_donation">เงินบริจาคทั่วไป (ลดหย่อนตามจริงไม่เกิน 10% ของเงินได้หลังหักค่าลดหย่อนอื่น):</label>
                    <input type="number" name="deduct_donation" id="deduct_donation" min="0" value="0">
                </div>
            </div>

            <button type="submit" name="btn" value="calculate">คำนวณภาษี</button>
            
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

            @if(isset($netIncome))
                <div class="result-box">
                    <span class="amount">{{ number_format($netIncome, 2) }}</span> บาท
                </div>
            @else
                <p>กรอกข้อมูลด้านบนแล้วกดปุ่มคำนวณภาษี</p>

            @endif
            <p>ยอดชำระภาษี :</p>

            <div class="taxpay">
                {{ $taxPayable }}
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} TaxCalculator. All rights reserved.</p>
    </footer>
</body>
</html>