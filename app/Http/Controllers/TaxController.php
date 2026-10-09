<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\TaxRecord;
use Illuminate\Support\Facades\Auth;

class TaxController extends Controller
{
    public function showForm()
    {
        $taxRecord = null;

        // ถ้าเป็น User ที่ล็อกอินแล้ว ให้ลองดึงข้อมูลปีล่าสุดจาก DB มาพรีโหลดใส่ฟอร์มล่วงหน้า
        if (Auth::check()) {
            $taxRecord = TaxRecord::where('user_id', Auth::id())
                ->orxderBy('tax_year', 'desc')
                ->first();
        }

        return view('calculate', compact('taxRecord'));
    }
    public function calculateTax(Request $request){
        $request -> validate([
            'married-status' => 'required|string',
            'child_count' => 'integer|min:0',
            'income_g1' => 'numeric|min:0',
            'income_g2' => 'numeric|min:0',
            'income_g3' => 'numeric|min:0',
            'income_g4' => 'numeric|min:0',
            'income_g5' => 'numeric|min:0',
            'income_g6' => 'numeric|min:0',
            'income_g7' => 'numeric|min:0',
            'income_g8' => 'numeric|min:0',
            'deduct_social' => 'numeric|min:0',
            'deduct_life' => 'numeric|min:0',
            'deduct_health' => 'numeric|min:0',
            'deduct_donation' => 'numeric|min:0',
        ]);


        // เงินได้พึงประเมิน
        $inc_g1 = $request -> input('income_g1','0');
        $inc_g2 = $request -> input('income_g2','0');
        $inc_g3 = $request -> input('income_g3','0');
        $inc_g4 = $request -> input('income_g4','0');
        $inc_g5 = $request -> input('income_g5','0');
        $inc_g6 = $request -> input('income_g6','0');
        $inc_g7 = $request -> input('income_g7','0');
        $inc_g8 = $request -> input('income_g8','0');

        $all_income = $inc_g1 + $inc_g2 + $inc_g3 + $inc_g4 + $inc_g5 + $inc_g6 + $inc_g7 + $inc_g8 ;
        
        $expense = 0;

        //  ม.40(1) + ม.40(2) หักเหมารวมกันได้ 50% แต่ไม่เกิน 100,000 บาท
        $exp_1_2 = ($inc_g1 + $inc_g2) * 0.5;
        if ($exp_1_2 > 100000){
            $exp_1_2 = 100000;
        }
        $expense += $exp_1_2;

        // ม.40(3) หักเหมาได้ 50% ไม่เกิน 100,000 บาท
        $exp3 = $inc_g3 * 0.5;
        if ($exp3 > 100000){
            $exp3 = 100000;
        }
        $expense += $exp3;

        // ม.40(4) ดอกเบี้ย/เงินปันผล หักค่าใช้จ่ายไม่ได้เลยตามกฎหมาย

        // ม.40(5) ค่าเช่า (คิดแบบเหมาเฉลี่ย 30%)
        $expense += $inc_g5 * 0.3;

        // ม.40(6) วิชาชีพอิสระ (คิดสัดส่วนตามประเภทที่เลือก เช่น แพทย์ 60%, อื่นๆ 30%)
        $g6_type = $request->input('income_g6_type');

        if($g6_type === 'medical'){
            $rate_g6 = 0.6;
        }elseif($g6_type){
            $rate_g6 = 0.3;
        }else{
            $rate_g6 = 0;
        }

        $expense += $inc_g6 * $rate_g6;

        // ม.40(7) รับเหมาก่อสร้าง (หักเหมา 60%)
        $expense += $inc_g7 * 0.6;

        // ม.40(8) ค้าขาย/ธุรกิจทั่วไป (หักเหมา 60%)
        $expense += $inc_g8 * 0.6;

        // ค่าลดหย่อน
        $deduction = 0;
        $marriedStatus = $request->input('married-status');
        $childCount = $request->input('child_count', 0);
        $deductSocial = $request->input('deduct_social', 0);
        $deductLife = $request->input('deduct_life', 0);
        $deductHealth = $request->input('deduct_health', 0);
        $deductDonation = $request->input('deduct_donation', 0);

        // 1. ลดหย่อนส่วนตัวพื้นฐาน 60,000 บาท (ทุกคนได้สิทธิ์นี้)
        $deduction += 60000;

        // 2. ลดหย่อนสถานะสมรส
        if($marriedStatus === 'married_no_income'){
            $deduction += 60000;
        }

        // 3. ลดหย่อนบุตร (คนละ 30,000 บาท)
        $deduction = $childCount * 30000;

        // 4. ลดหย่อนผู้พิการ (ถ้าติ๊กเลือก ได้เพิ่ม 60,000 บาท)
        if($request->has('is_disable')){
            $deduction += 60000;
        }

        // 5. ลดหย่อนกลุ่มประกันและการออม (Cap ยอดสูงสุดตามกฎหมาย)
        if ($deductSocial > 9000) $deductSocial = 9000;

        if ($deductLife > 100000) $deductLife = 100000;

        if ($deductHealth > 25000) $deductHealth = 25000;

        if (($deductLife + $deductHealth) > 100000) {
            $deductHealth = 100000 - $deductLife;
        }

        $deduction = ($deductSocial + $deductLife + $deductHealth);

        // D. หาเงินได้สุทธิขั้นต้นก่อนหักเงินบริจาค
        $netIncomeBeforeDonation = $all_income - $expense - $deduction;

        if($netIncomeBeforeDonation < 0){
            $netIncomeBeforeDonation = 0;
        }

        // E. คำนวณหักเงินบริจาค (ต้องไม่เกิน 10% ของเงินได้สุทธิขั้นต้น)
        $maxDonationAllowed = $netIncomeBeforeDonation * 0.10;
        if ($deductDonation > $maxDonationAllowed) {
            $deductDonation = $maxDonationAllowed;
        }

        // F. คำนวณสรุปเงินได้สุทธิ (Net Income) สิ้นสุด

        $netIncome = $netIncomeBeforeDonation - $deductDonation;
        if ($netIncome < 0) $netIncome = 0;

        // G. คำนวณภาษีที่ต้องจ่ายจากเงินได้สุทธิ (ตามตารางขั้นบันได)
        $taxPayable = $this->calculateStepTax($netIncome);

        // 3. ตรวจสอบ Action: ถ้ากดปุ่ม "save" และล็อกอินอยู่ ให้เซฟลง DB
        // $action = $request->input('action');
        // if ($action === 'save' && Auth::check()) {
        //     TaxRecord::updateOrCreate(
        //         [
        //             'user_id' => Auth::id(),
        //             'tax_year' => date('Y'),
        //         ],
        //         [
        //             'married_status' => $marriedStatus,
        //             'child_count' => $childCount,
        //             'is_disable' => $request->has('is_disable'),
        //             'income_g1' => $inc_g1,
        //             'income_g2' => $inc_g2,
        //             'income_g3' => $inc_g3,
        //             'income_g4' => $inc_g4,
        //             'income_g5' => $inc_g5,
        //             'income_g6' => $inc_g6,
        //             'income_g7' => $inc_g7,
        //             'income_g8' => $inc_g8,
        //             'deduct_social' => $request->input('deduct_social', 0),
        //             'deduct_life' => $request->input('deduct_life', 0),
        //             'deduct_health' => $request->input('deduct_health', 0),
        //             'deduct_donation' => $request->input('deduct_donation', 0),
        //         ]
        //     );

        //     return redirect()->back()->with([
        //         'success' => 'บันทึกข้อมูลภาษีเรียบร้อยแล้ว!',
        //         'netIncome' => $netIncome,
        //         'taxPayable' => $taxPayable
        //     ]);
        // }

        return view('calculate',compact('netIncome','taxPayable'));
    }

    public function calculateStepTax(int $netIncome){
        $tax = 0;

        if($netIncome <= 150000){
            return 0;
        }

        if($netIncome > 150000){
            $range = min($netIncome,300000) - 1500000;
            $tax += $range * 0.05;
        }

        if($netIncome > 300000){
            $range = min($netIncome,500000) - 3000000;
            $tax += $range * 0.10;
        }

        if($netIncome > 500000){
            $range = min($netIncome,750000) - 5000000;
            $tax += $range * 0.15;
        }

        if($netIncome > 750000){
            $range = min($netIncome,1000000) - 5000000;
            $tax += $range * 0.20;
        }

        if($netIncome > 1000000){
            $range = min($netIncome,2000000) - 10000000;
            $tax += $range * 0.25;
        }

        if($netIncome > 2000000){
            $range = min($netIncome,5000000) - 2000000;
            $tax += $range * 0.30;
        }

        if($netIncome > 5000000){
            $range = $netIncome - 5000000;
            $tax += $range * 0.05;
        }
        return $tax;
    }
}
