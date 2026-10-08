<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <h1>Personal Tax</h1>
        <nav>
            <a href="/">หน้าหลัก</a>
            <a href="/knowledge">ความรู้เกี่ยวกับภาษี</a>
            <a href="/about">เกี่ยวกับ</a>
            
            <!-- ส่วนสลับสิทธิ์การเข้าสู่ระบบ -->
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

    <main class="tax-knowledge-container">
    <!-- Header หัวข้อหลัก -->
        <header class="page-header">
            <h1>ความรู้เบื้องต้นเกี่ยวกับภาษีเงินได้บุคคลธรรมดา</h1>
            <p>คู่มือทำความเข้าใจเรื่องภาษีแบบง่ายๆ สำหรับคนไทยทุกคน</p>
        </header>

        <hr>

        <!-- 1. ทำไมต้องจ่ายภาษี -->
        <section class="tax-why">
            <h2>1. ทำไมเราต้องจ่ายภาษี?</h2>
            <p>ภาษีเงินได้บุคคลธรรมดา คือเงินที่ประชาชนที่มีรายได้มีหน้าที่ต้องนำส่งให้รัฐบาลตามกฎหมาย เพื่อให้นำเงินเหล่านั้นไปใช้พัฒนาประเทศชาติในด้านต่างๆ เช่น:</p>
            <ul>
                <li>การสร้างโครงสร้างพื้นฐาน (ถนน, รถไฟฟ้า, ระบบขนส่งมวลชน)</li>
                <li>การสาธารณสุข (โรงพยาบาลรัฐ, สิทธิ์การรักษาพยาบาล)</li>
                <li>การศึกษา และระบบสวัสดิการแห่งรัฐ</li>
                <li>การรักษาความสงบเรียบร้อยและความมั่นคงของประเทศ</li>
            </ul>
        </section>

        <hr>

        <!-- 2. ใครต้องจ่ายบ้าง -->
        <section class="tax-who">
            <h2>2. ใครมีหน้าที่ต้องยื่นภาษีบ้าง?</h2>
            <p>คนไทยหรือผู้ที่มีรายเกิดขึ้นในประเทศไทยทุกคนที่มี <strong>เงินได้พึงประเมิน (รายได้รวมทั้งปี)</strong> ถึงเกณฑ์ที่กำหนด มีหน้าที่ต้องยื่นแบบภาษี แม้ว่าคำนวณออกมาแล้วอาจจะชำระภาษีเป็น 0 บาทก็ตาม โดยเกณฑ์ขั้นต่ำมีดังนี้:</p>
            
            <h3>กรณีคนโสด</h3>
            <ul>
                <li>มีรายได้จากเงินเดือนประจำ (ม.40(1)) เกิน <strong>120,000 บาทต่อปี</strong></li>
                <li>มีรายได้ประเภทอื่น หรือรายได้หลายทางรวมกัน เกิน <strong>60,000 บาทต่อปี</strong></li>
            </ul>

            <h3>กรณีสมรส</h3>
            <ul>
                <li>มีรายได้จากเงินเดือนประจำ (ม.40(1)) เกิน <strong>220,000 บาทต่อปี</strong></li>
                <li>มีรายได้ประเภทอื่น หรือรายได้หลายทางรวมกัน เกิน <strong>120,000 บาทต่อปี</strong></li>
            </ul>
        </section>

        <hr>

        <!-- 3. วิธีคำนวณรายได้สุทธิ -->
        <section class="tax-calculation">
            <h2>3. วิธีคำนวณ "เงินได้สุทธิ" สำหรับคิดภาษี</h2>
            <p>การคิดภาษีของไทยจะไม่ได้คิดจากรายได้ทั้งหมดโดยตรง แต่จะคิดจาก <strong>"เงินได้สุทธิ"</strong> ซึ่งมีสูตรการคำนวณขั้นพื้นฐานดังนี้:</p>
            
            <div class="formula-box" style="background: #f1f5f9; padding: 15px; border-radius: 6px; font-weight: bold; font-size: 16px; margin: 15px 0;">
                เงินได้สุทธิ = เงินได้พึงประเมิน (รายได้ทั้งปี) - ค่าใช้จ่าย - ค่าลดหย่อน
            </div>

            <p><strong>รายละเอียดของสมการ:</strong></p>
            <ul>
                <li><strong>เงินได้พึงประเมิน:</strong> รายได้ทุกประเภทของคุณรวมกันตลอดทั้งปี (ม.40(1) ถึง ม.40(8))</li>
                <li><strong>ค่าใช้จ่าย:</strong> สิทธิ์การหักต้นทุนตามที่กฎหมายกำหนด (เช่น เงินเดือนหักได้ 50% สูงสุด 100,000 บาท)</li>
                <li><strong>ค่าลดหย่อน:</strong> สิทธิ์ส่วนตัวและภาระที่รัฐช่วยสนับสนุน (เช่น ลดหย่อนส่วนตัว 60,000 บาท, ประกันสังคม, บุตร, ประกันชีวิต)</li>
            </ul>
        </section>

        <hr>

        <!-- 4. ตารางภาษีขั้นบันได -->
        <section class="tax-brackets">
            <h2>4. ตารางอัตราภาษีเงินได้บุคคลธรรมดา (แบบขั้นบันได)</h2>
            <p>เมื่อคำนวณได้ "เงินได้สุทธิ" จากข้อ 3 แล้ว จะนำตัวเลขนั้นมาเทียบกับตารางอัตราภาษีนี้เพื่อคิดเงินภาษีที่ต้องจ่ายจริง:</p>
            
            <table border="1" style="width: 100%; border-collapse: collapse; text-align: left; margin-top: 15px;">
                <thead>
                    <tr style="background: #e2e8f0;">
                        <th style="padding: 10px;">เงินได้สุทธิต่อปี (บาท)</th>
                        <th style="padding: 10px;">อัตราภาษี (%)</th>
                        <th style="padding: 10px;">ภาษีสะสมสูงสุดของขั้น (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px;">0 - 150,000</td>
                        <td style="padding: 10px;">ยกเว้นภาษี</td>
                        <td style="padding: 10px;">0</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">150,001 - 300,000</td>
                        <td style="padding: 10px;">5%</td>
                        <td style="padding: 10px;">7,500</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">300,001 - 500,000</td>
                        <td style="padding: 10px;">10%</td>
                        <td style="padding: 10px;">27,500</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">500,001 - 750,000</td>
                        <td style="padding: 10px;">15%</td>
                        <td style="padding: 10px;">65,000</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">750,001 - 1,000,000</td>
                        <td style="padding: 10px;">20%</td>
                        <td style="padding: 10px;">115,000</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">1,000,001 - 2,000,000</td>
                        <td style="padding: 10px;">25%</td>
                        <td style="padding: 10px;">365,000</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">2,000,001 - 5,000,000</td>
                        <td style="padding: 10px;">30%</td>
                        <td style="padding: 10px;">1,265,000</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">5,000,001 บาทขึ้นไป</td>
                        <td style="padding: 10px;">35%</td>
                        <td style="padding: 10px;">-</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- ปุ่มนำทางส่วนท้ายหน้า -->
        <footer style="margin-top: 40px; text-align: center;">
            <a href="" class="btn-primary" style="display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                🧮 มีข้อมูลครบแล้ว? ไปเริ่มคำนวณภาษีกันเลย
            </a>
        </footer>
    </main>

</body>
</html>