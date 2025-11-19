@extends('layouts.spa')

@section('title', 'Reports')

@section('breadcrumb')
    <span class="font-semibold text-gray-900">Reports</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
            <p class="text-gray-600 mt-1">Generate and view comprehensive reports for your school</p>
        </div>
        <button class="btn btn-primary">
            <i class="fas fa-file-export mr-2"></i>
            Export All Data
        </button>
    </div>

    <!-- Report Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($reportCategories as $category)
        <a href="{{ route($category['route']) }}"
           class="card hover:shadow-xl transition-all duration-300 group">
            <div class="card-body">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 rounded-lg bg-{{ $category['color'] }}-100 flex items-center justify-center group-hover:bg-{{ $category['color'] }}-500 transition-colors">
                        <i class="fas {{ $category['icon'] }} text-2xl text-{{ $category['color'] }}-600 group-hover:text-white"></i>
                    </div>
                    <span class="badge badge-{{ $category['color'] === 'blue' ? 'primary' : ($category['color'] === 'green' ? 'success' : ($category['color'] === 'orange' ? 'warning' : 'info')) }}">
                        {{ $category['count'] }} Reports
                    </span>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $category['name'] }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ $category['description'] }}</p>

                <div class="flex items-center text-{{ $category['color'] }}-600 font-medium text-sm group-hover:translate-x-2 transition-transform">
                    <span>View Reports</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Reports</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">14</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Generated Today</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">7</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Scheduled Reports</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">3</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-clock text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Exports This Month</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">42</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="fas fa-download text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Recently Generated Reports</h2>
        </div>
        <div class="card-body">
            <div class="space-y-3">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Monthly Attendance Report</p>
                            <p class="text-sm text-gray-600">Generated 2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="badge badge-success">PDF</span>
                        <button class="text-primary-600 hover:text-primary-700">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-chart-bar text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Class Performance Analysis</p>
                            <p class="text-sm text-gray-600">Generated yesterday</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="badge badge-primary">Excel</span>
                        <button class="text-primary-600 hover:text-primary-700">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-user-graduate text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Student Enrollment Report</p>
                            <p class="text-sm text-gray-600">Generated 3 days ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="badge badge-success">PDF</span>
                        <button class="text-primary-600 hover:text-primary-700">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
