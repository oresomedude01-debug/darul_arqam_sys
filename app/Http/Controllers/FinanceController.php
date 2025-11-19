<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        // Demo finance data
        $stats = [
            'total_revenue' => 125750.00,
            'pending_payments' => 23400.00,
            'collected_this_month' => 45200.00,
            'overdue_amount' => 8900.00
        ];

        $recentTransactions = [
            ['id' => 1, 'student' => 'Ahmed Ali', 'amount' => 500.00, 'type' => 'Tuition Fee', 'date' => '2025-01-18', 'status' => 'paid'],
            ['id' => 2, 'student' => 'Fatima Hassan', 'amount' => 350.00, 'type' => 'Book Fee', 'date' => '2025-01-17', 'status' => 'paid'],
            ['id' => 3, 'student' => 'Omar Mohammed', 'amount' => 500.00, 'type' => 'Tuition Fee', 'date' => '2025-01-15', 'status' => 'pending'],
            ['id' => 4, 'student' => 'Aisha Abdullah', 'amount' => 200.00, 'type' => 'Transport Fee', 'date' => '2025-01-14', 'status' => 'overdue'],
            ['id' => 5, 'student' => 'Yusuf Ibrahim', 'amount' => 500.00, 'type' => 'Tuition Fee', 'date' => '2025-01-12', 'status' => 'paid'],
        ];

        $feeStructure = [
            ['category' => 'Tuition Fee', 'amount' => 500.00, 'frequency' => 'Monthly', 'students' => 245],
            ['category' => 'Book Fee', 'amount' => 350.00, 'frequency' => 'Yearly', 'students' => 245],
            ['category' => 'Transport Fee', 'amount' => 200.00, 'frequency' => 'Monthly', 'students' => 180],
            ['category' => 'Lab Fee', 'amount' => 150.00, 'frequency' => 'Semester', 'students' => 120],
            ['category' => 'Sports Fee', 'amount' => 100.00, 'frequency' => 'Yearly', 'students' => 200],
        ];

        return view('finance.index', compact('stats', 'recentTransactions', 'feeStructure'));
    }

    public function payments()
    {
        // Demo payment data
        $payments = [
            ['id' => 1, 'student' => 'Ahmed Ali', 'student_id' => 'STD-001', 'amount' => 500.00, 'fee_type' => 'Tuition', 'method' => 'Cash', 'date' => '2025-01-18', 'receipt' => 'RCP-001'],
            ['id' => 2, 'student' => 'Fatima Hassan', 'student_id' => 'STD-002', 'amount' => 350.00, 'fee_type' => 'Books', 'method' => 'Bank Transfer', 'date' => '2025-01-17', 'receipt' => 'RCP-002'],
            ['id' => 3, 'student' => 'Yusuf Ibrahim', 'student_id' => 'STD-003', 'amount' => 500.00, 'fee_type' => 'Tuition', 'method' => 'Credit Card', 'date' => '2025-01-12', 'receipt' => 'RCP-003'],
            ['id' => 4, 'student' => 'Maryam Ali', 'student_id' => 'STD-004', 'amount' => 200.00, 'fee_type' => 'Transport', 'method' => 'Cash', 'date' => '2025-01-11', 'receipt' => 'RCP-004'],
            ['id' => 5, 'student' => 'Hassan Ahmed', 'student_id' => 'STD-005', 'amount' => 500.00, 'fee_type' => 'Tuition', 'method' => 'Bank Transfer', 'date' => '2025-01-10', 'receipt' => 'RCP-005'],
        ];

        $paymentStats = [
            'total_collected' => 45200.00,
            'cash_payments' => 15800.00,
            'bank_transfers' => 18900.00,
            'card_payments' => 10500.00
        ];

        return view('finance.payments', compact('payments', 'paymentStats'));
    }
}
