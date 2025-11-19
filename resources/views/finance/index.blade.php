@extends('layouts.spa')

@section('title', 'Finance Management')

@section('breadcrumb')
    <span class="font-semibold text-gray-900">Finance</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Finance Management</h1>
            <p class="text-gray-600 mt-1">Manage school fees and payments</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('finance.payments') }}" class="btn btn-secondary">
                <i class="fas fa-list mr-2"></i>
                View Payments
            </a>
            <button class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Record Payment
            </button>
        </div>
    </div>

    <!-- Financial Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Revenue</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($stats['total_revenue'], 2) }}</h3>
                </div>
                <i class="fas fa-dollar-sign text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-yellow-500 to-yellow-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pending Payments</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($stats['pending_payments'], 2) }}</h3>
                </div>
                <i class="fas fa-clock text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Collected This Month</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($stats['collected_this_month'], 2) }}</h3>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-red-500 to-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Overdue Amount</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($stats['overdue_amount'], 2) }}</h3>
                </div>
                <i class="fas fa-exclamation-triangle text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Fee Structure -->
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Fee Structure</h2>
            <button class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-1"></i>
                Add Fee Category
            </button>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fee Category</th>
                            <th>Amount</th>
                            <th>Frequency</th>
                            <th>Students</th>
                            <th>Expected Revenue</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feeStructure as $fee)
                        <tr>
                            <td class="font-medium">{{ $fee['category'] }}</td>
                            <td class="font-bold text-green-600">${{ number_format($fee['amount'], 2) }}</td>
                            <td><span class="badge badge-primary">{{ $fee['frequency'] }}</span></td>
                            <td>{{ $fee['students'] }} students</td>
                            <td class="font-bold">${{ number_format($fee['amount'] * $fee['students'], 2) }}</td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <button class="text-blue-600 hover:text-blue-700" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-700" title="Delete">
                                        <i class="fas fa-trash"></i>
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

    <!-- Recent Transactions -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Recent Transactions</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Fee Type</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $transaction)
                        <tr>
                            <td class="font-medium">{{ $transaction['student'] }}</td>
                            <td>{{ $transaction['type'] }}</td>
                            <td class="font-bold text-green-600">${{ number_format($transaction['amount'], 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction['date'])->format('M d, Y') }}</td>
                            <td>
                                @if($transaction['status'] === 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @elseif($transaction['status'] === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Overdue</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <button class="text-primary-600 hover:text-primary-700" title="View Receipt">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                    @if($transaction['status'] !== 'paid')
                                    <button class="text-green-600 hover:text-green-700" title="Mark as Paid">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="card-body">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-file-invoice-dollar text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Generate Invoices</h3>
                        <p class="text-sm text-gray-600">Create fee invoices</p>
                    </div>
                    <button class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-green-50 to-green-100 border-green-200">
            <div class="card-body">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-green-600 flex items-center justify-center">
                        <i class="fas fa-bell text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Send Reminders</h3>
                        <p class="text-sm text-gray-600">Notify pending payments</p>
                    </div>
                    <button class="btn btn-sm btn-success">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="card-body">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-purple-600 flex items-center justify-center">
                        <i class="fas fa-chart-pie text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Financial Report</h3>
                        <p class="text-sm text-gray-600">View detailed reports</p>
                    </div>
                    <button class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
