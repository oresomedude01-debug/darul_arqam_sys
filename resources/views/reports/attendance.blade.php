@extends('layouts.spa')

@section('title', 'Attendance Reports')

@section('breadcrumb')
    <a href="{{ route('reports.index') }}" class="text-gray-600 hover:text-gray-900">Reports</a>
    <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
    <span class="font-semibold text-gray-900">Attendance Reports</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Attendance Reports</h1>
            <p class="text-gray-600 mt-1">Last 30 days ({{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }})</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="btn btn-secondary">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>
            <button class="btn btn-primary">
                <i class="fas fa-file-export mr-2"></i>
                Export Report
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Present</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $attendanceData->sum('present') }}</h3>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-red-500 to-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Absent</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $attendanceData->sum('absent') }}</h3>
                </div>
                <i class="fas fa-times-circle text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-yellow-500 to-yellow-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Late</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $attendanceData->sum('late') }}</h3>
                </div>
                <i class="fas fa-clock text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Attendance Rate</p>
                    @php
                        $total = $attendanceData->sum('total');
                        $present = $attendanceData->sum('present');
                        $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                    @endphp
                    <h3 class="text-3xl font-bold mt-2">{{ $rate }}%</h3>
                </div>
                <i class="fas fa-percent text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Daily Attendance Chart -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Daily Attendance Trend</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Late</th>
                            <th>Attendance Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceData as $day)
                        <tr>
                            <td class="font-medium">{{ Carbon\Carbon::parse($day->date)->format('D, M d, Y') }}</td>
                            <td>{{ $day->total }}</td>
                            <td><span class="badge badge-success">{{ $day->present }}</span></td>
                            <td><span class="badge badge-danger">{{ $day->absent }}</span></td>
                            <td><span class="badge badge-warning">{{ $day->late }}</span></td>
                            <td>
                                @php
                                    $dayRate = $day->total > 0 ? round(($day->present / $day->total) * 100, 1) : 0;
                                @endphp
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 progress">
                                        <div class="progress-bar {{ $dayRate >= 90 ? 'bg-green-600' : ($dayRate >= 75 ? 'bg-yellow-600' : 'bg-red-600') }}"
                                             style="width: {{ $dayRate }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium">{{ $dayRate }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No attendance data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Class-wise Attendance -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Class-wise Attendance</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Students</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Late</th>
                            <th>Attendance Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classAttendance as $class)
                        <tr>
                            <td class="font-medium">{{ $class['class_name'] }}</td>
                            <td>{{ $class['student_count'] }}</td>
                            <td><span class="badge badge-success">{{ $class['present_count'] }}</span></td>
                            <td><span class="badge badge-danger">{{ $class['absent_count'] }}</span></td>
                            <td><span class="badge badge-warning">{{ $class['late_count'] }}</span></td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 progress">
                                        <div class="progress-bar {{ $class['attendance_rate'] >= 90 ? 'bg-green-600' : ($class['attendance_rate'] >= 75 ? 'bg-yellow-600' : 'bg-red-600') }}"
                                             style="width: {{ $class['attendance_rate'] }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium">{{ $class['attendance_rate'] }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No class attendance data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
