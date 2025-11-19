@extends('layouts.spa')

@section('title', 'Payments')

@section('breadcrumb')
    <a href="{{ route('finance.index') }}" class="text-gray-600 hover:text-gray-900">Finance</a>
    <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
    <span class="font-semibold text-gray-900">Payments</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Records</h1>
            <p class="text-gray-600 mt-1">View and manage all payment transactions</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="btn btn-secondary">
                <i class="fas fa-file-export mr-2"></i>
                Export
            </button>
            <button class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Record Payment
            </button>
        </div>
    </div>

    <!-- Payment Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Collected</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($paymentStats['total_collected'], 2) }}</h3>
                </div>
                <i class="fas fa-money-bill-wave text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Cash Payments</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($paymentStats['cash_payments'], 2) }}</h3>
                </div>
                <i class="fas fa-cash-register text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-purple-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Bank Transfers</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($paymentStats['bank_transfers'], 2) }}</h3>
                </div>
                <i class="fas fa-university text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-orange-500 to-orange-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Card Payments</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($paymentStats['card_payments'], 2) }}</h3>
                </div>
                <i class="fas fa-credit-card text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" placeholder="Search by student name, receipt, or ID..."
                               class="form-input w-full pl-10">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <select class="form-select">
                        <option>All Payment Methods</option>
                        <option>Cash</option>
                        <option>Bank Transfer</option>
                        <option>Credit Card</option>
                    </select>
                    <input type="date" class="form-input">
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Records Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Payment Transactions</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Receipt #</th>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Fee Type</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td class="font-mono text-sm font-medium">{{ $payment['receipt'] }}</td>
                            <td class="font-medium">{{ $payment['student'] }}</td>
                            <td class="font-mono text-sm">{{ $payment['student_id'] }}</td>
                            <td><span class="badge badge-primary">{{ $payment['fee_type'] }}</span></td>
                            <td class="font-bold text-green-600">${{ number_format($payment['amount'], 2) }}</td>
                            <td>
                                @if($payment['method'] === 'Cash')
                                    <span class="badge badge-success">
                                        <i class="fas fa-money-bill mr-1"></i>
                                        Cash
                                    </span>
                                @elseif($payment['method'] === 'Bank Transfer')
                                    <span class="badge badge-info">
                                        <i class="fas fa-university mr-1"></i>
                                        Bank Transfer
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-credit-card mr-1"></i>
                                        Credit Card
                                    </span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($payment['date'])->format('M d, Y') }}</td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <button class="text-primary-600 hover:text-primary-700" title="View Receipt">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                    <button class="text-blue-600 hover:text-blue-700" title="Print">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-700" title="Send Email">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
