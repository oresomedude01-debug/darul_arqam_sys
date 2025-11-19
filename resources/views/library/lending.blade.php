@extends('layouts.spa')

@section('title', 'Library Lending')

@section('breadcrumb')
    <a href="{{ route('library.index') }}" class="text-gray-600 hover:text-gray-900">Library</a>
    <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
    <span class="font-semibold text-gray-900">Lending Management</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lending Management</h1>
            <p class="text-gray-600 mt-1">Track and manage book loans</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('library.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Catalog
            </a>
            <button class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                New Loan
            </button>
        </div>
    </div>

    <!-- Lending Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Active Loans</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($lendingStats['active_loans']) }}</h3>
                </div>
                <i class="fas fa-book-reader text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-red-500 to-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Overdue</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($lendingStats['overdue_loans']) }}</h3>
                </div>
                <i class="fas fa-exclamation-triangle text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Returned Today</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($lendingStats['returned_today']) }}</h3>
                </div>
                <i class="fas fa-undo text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-purple-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">This Month</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($lendingStats['total_loans_this_month']) }}</h3>
                </div>
                <i class="fas fa-chart-line text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" placeholder="Search by student name or book title..."
                               class="form-input w-full pl-10">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Overdue</option>
                        <option>Returned</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrowed Books Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Borrowed Books</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Book Title</th>
                            <th>Borrow Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowedBooks as $loan)
                        <tr>
                            <td class="font-medium">{{ $loan['student_name'] }}</td>
                            <td class="font-mono text-sm">{{ $loan['student_id'] }}</td>
                            <td>{{ $loan['book_title'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($loan['borrow_date'])->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($loan['due_date'])->format('M d, Y') }}</td>
                            <td>
                                @if($loan['status'] === 'active')
                                    @if($loan['days_remaining'] < 3)
                                        <span class="badge badge-warning">Due Soon ({{ $loan['days_remaining'] }} days)</span>
                                    @else
                                        <span class="badge badge-success">Active</span>
                                    @endif
                                @elseif($loan['status'] === 'overdue')
                                    <span class="badge badge-danger">Overdue ({{ abs($loan['days_remaining']) }} days)</span>
                                @else
                                    <span class="badge badge-secondary">Returned</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    @if($loan['status'] !== 'returned')
                                    <button class="btn btn-sm btn-primary" title="Return Book">
                                        <i class="fas fa-undo mr-1"></i>
                                        Return
                                    </button>
                                    <button class="btn btn-sm btn-secondary" title="Renew">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                    @else
                                    <span class="text-sm text-gray-500">-</span>
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
                        <i class="fas fa-hand-holding text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Lend a Book</h3>
                        <p class="text-sm text-gray-600">Issue a new book loan</p>
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
                        <i class="fas fa-undo text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Return Books</h3>
                        <p class="text-sm text-gray-600">Process book returns</p>
                    </div>
                    <button class="btn btn-sm btn-success">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-orange-50 to-orange-100 border-orange-200">
            <div class="card-body">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-orange-600 flex items-center justify-center">
                        <i class="fas fa-bell text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Send Reminders</h3>
                        <p class="text-sm text-gray-600">Notify overdue borrowers</p>
                    </div>
                    <button class="btn btn-sm btn-warning">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
