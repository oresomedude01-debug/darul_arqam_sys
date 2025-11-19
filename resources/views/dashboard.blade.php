@extends('layouts.spa')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="font-semibold text-gray-900">Dashboard</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome back, Admin!</h1>
                <p class="text-primary-100">Here's what's happening in your school today.</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-school text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Students -->
        <div class="stat-card from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Students</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($totalStudents) }}</h3>
                    <p class="text-blue-100 text-xs mt-2">
                        <i class="fas fa-users"></i> Enrolled students
                    </p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <i class="fas fa-user-graduate text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="stat-card from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Teachers</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($totalTeachers) }}</h3>
                    <p class="text-green-100 text-xs mt-2">
                        <i class="fas fa-chalkboard-teacher"></i> Teaching staff
                    </p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <i class="fas fa-chalkboard-teacher text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Classes -->
        <div class="stat-card from-purple-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Classes</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($totalClasses) }}</h3>
                    <p class="text-purple-100 text-xs mt-2">
                        <i class="fas fa-book-open"></i> Active classes
                    </p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <i class="fas fa-book-open text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Attendance Today -->
        <div class="stat-card from-orange-500 to-orange-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Attendance Today</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $attendancePercentage > 0 ? $attendancePercentage . '%' : 'N/A' }}</h3>
                    <p class="text-orange-100 text-xs mt-2">
                        <i class="fas fa-clipboard-check"></i> {{ date('l, M d, Y') }}
                    </p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <i class="fas fa-clipboard-check text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Attendance Chart -->
        <div class="lg:col-span-2 card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Attendance Overview</h2>
                <div class="flex items-center space-x-2">
                    <select class="form-select text-sm py-1">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>This Month</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <!-- Chart Placeholder -->
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg"
                     x-data="{
                         days: @json($last7Days),
                         attendance: @json($attendanceData)
                     }">
                    <div class="w-full h-full p-4">
                        <div class="flex items-end justify-between h-full space-x-2">
                            <template x-for="(day, index) in days" :key="index">
                                <div class="flex-1 flex flex-col items-center space-y-2">
                                    <div class="w-full bg-primary-600 rounded-t hover:bg-primary-700 transition-colors cursor-pointer relative group"
                                         :style="`height: ${attendance[index]}%`"
                                         :title="`${attendance[index]}%`">
                                        <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap" x-text="`${attendance[index]}%`"></span>
                                    </div>
                                    <span class="text-xs text-gray-600" x-text="day"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
            </div>
            <div class="card-body space-y-3">
                <a href="{{ route('students.create') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-primary-500 hover:bg-primary-50 transition-all group">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center group-hover:bg-blue-500 transition-colors">
                        <i class="fas fa-user-plus text-blue-600 group-hover:text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Add Student</p>
                        <p class="text-xs text-gray-500">Register new student</p>
                    </div>
                </a>

                <a href="{{ route('teachers.create') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-green-500 hover:bg-green-50 transition-all group">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center group-hover:bg-green-500 transition-colors">
                        <i class="fas fa-chalkboard-teacher text-green-600 group-hover:text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Add Teacher</p>
                        <p class="text-xs text-gray-500">Register new teacher</p>
                    </div>
                </a>

                <a href="{{ route('attendance.index') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-orange-500 hover:bg-orange-50 transition-all group">
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center group-hover:bg-orange-500 transition-colors">
                        <i class="fas fa-clipboard-check text-orange-600 group-hover:text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Mark Attendance</p>
                        <p class="text-xs text-gray-500">Take today's attendance</p>
                    </div>
                </a>

                <a href="{{ route('grades.index') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 hover:border-purple-500 hover:bg-purple-50 transition-all group">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center group-hover:bg-purple-500 transition-colors">
                        <i class="fas fa-chart-line text-purple-600 group-hover:text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Enter Grades</p>
                        <p class="text-xs text-gray-500">Record exam results</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Upcoming Events -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
                <a href="#" class="text-sm text-primary-600 hover:text-primary-700">View All</a>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                    <!-- Activity Item -->
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $activity['icon'] }} text-{{ $activity['color'] }}-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">{!! $activity['title'] !!}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p>No recent activities</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Upcoming Events</h2>
                <a href="{{ route('calendar.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View Calendar</a>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @forelse($upcomingEvents as $event)
                    @php
                        $colorMap = [
                            'holiday' => 'red',
                            'examination' => 'green',
                            'meeting' => 'blue',
                            'sports' => 'orange',
                            'cultural' => 'purple',
                            'other' => 'gray'
                        ];
                        $color = $colorMap[$event->type] ?? 'blue';
                        $startDate = \Carbon\Carbon::parse($event->start_date);
                    @endphp
                    <!-- Event Item -->
                    <div class="flex items-start space-x-4 p-3 bg-{{ $color }}-50 rounded-lg border border-{{ $color }}-200">
                        <div class="text-center flex-shrink-0">
                            <div class="w-12 h-12 bg-{{ $color }}-600 text-white rounded-lg flex flex-col items-center justify-center">
                                <span class="text-xs font-medium">{{ $startDate->format('M') }}</span>
                                <span class="text-lg font-bold">{{ $startDate->format('d') }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900">{{ $event->title }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($event->start_time)
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                    @if($event->end_time)
                                        - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                    @endif
                                @else
                                    All Day
                                @endif
                            </p>
                            <span class="badge badge-{{ $color === 'red' ? 'danger' : ($color === 'green' ? 'success' : ($color === 'orange' ? 'warning' : 'primary')) }} mt-2">
                                {{ ucfirst($event->type) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-calendar-alt text-4xl mb-3"></i>
                        <p>No upcoming events</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Overview -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Class Performance Overview</h2>
            <select class="form-select text-sm py-1">
                <option>All Classes</option>
                <option>Class 10</option>
                <option>Class 11</option>
                <option>Class 12</option>
            </select>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Total Students</th>
                            <th>Average Grade</th>
                            <th>Attendance Rate</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classPerformance as $class)
                        <tr>
                            <td class="font-medium">{{ $class['name'] }}</td>
                            <td>{{ $class['student_count'] }}</td>
                            <td>
                                <div class="flex items-center">
                                    <span class="font-medium text-{{ $class['avg_grade'] >= 80 ? 'green' : ($class['avg_grade'] >= 70 ? 'blue' : 'yellow') }}-600">
                                        {{ $class['avg_grade'] > 0 ? $class['avg_grade'] . '%' : 'N/A' }}
                                    </span>
                                    @if($class['avg_grade'] > 0)
                                    <div class="ml-2 flex-1 progress">
                                        <div class="progress-bar bg-{{ $class['avg_grade'] >= 80 ? 'green' : ($class['avg_grade'] >= 70 ? 'blue' : 'yellow') }}-600"
                                             style="width: {{ $class['avg_grade'] }}%"></div>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($class['attendance_rate'] > 0)
                                    <span class="badge badge-{{ $class['attendance_rate'] >= 90 ? 'success' : ($class['attendance_rate'] >= 80 ? 'primary' : 'warning') }}">
                                        {{ $class['attendance_rate'] }}%
                                    </span>
                                @else
                                    <span class="badge badge-secondary">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $class['status_badge'] }}">{{ $class['status'] }}</span>
                            </td>
                            <td>
                                <a href="{{ route('classes.show', $class['id']) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                <i class="fas fa-school text-4xl mb-3"></i>
                                <p>No classes available</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Dashboard specific scripts
    console.log('Dashboard loaded successfully!');

    // Initialize tooltips for chart bars
    document.addEventListener('alpine:initialized', () => {
        console.log('Alpine.js initialized on dashboard');
    });
</script>
@endpush
@endsection
