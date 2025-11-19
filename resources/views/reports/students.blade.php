@extends('layouts.spa')

@section('title', 'Student Reports')

@section('breadcrumb')
    <a href="{{ route('reports.index') }}" class="text-gray-600 hover:text-gray-900">Reports</a>
    <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
    <span class="font-semibold text-gray-900">Student Reports</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Student Reports</h1>
            <p class="text-gray-600 mt-1">Student enrollment and distribution statistics</p>
        </div>
        <button class="btn btn-primary">
            <i class="fas fa-file-export mr-2"></i>
            Export Report
        </button>
    </div>

    <!-- Student Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Students</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($totalStudents) }}</h3>
                </div>
                <i class="fas fa-users text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Active</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($activeStudents) }}</h3>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-yellow-500 to-yellow-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Inactive</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($inactiveStudents) }}</h3>
                </div>
                <i class="fas fa-pause-circle text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-purple-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Graduated</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($graduatedStudents) }}</h3>
                </div>
                <i class="fas fa-graduation-cap text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Students by Class -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Students by Class</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Student Count</th>
                            <th>Capacity</th>
                            <th>Utilization</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentsByClass as $class)
                        <tr>
                            <td class="font-medium">{{ $class['class_name'] }}</td>
                            <td>{{ $class['student_count'] }}</td>
                            <td>{{ $class['capacity'] }}</td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 progress">
                                        <div class="progress-bar {{ $class['utilization'] >= 90 ? 'bg-red-600' : ($class['utilization'] >= 75 ? 'bg-yellow-600' : 'bg-green-600') }}"
                                             style="width: {{ $class['utilization'] }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium">{{ $class['utilization'] }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($class['utilization'] >= 90)
                                    <span class="badge badge-danger">Nearly Full</span>
                                @elseif($class['utilization'] >= 75)
                                    <span class="badge badge-warning">Filling Up</span>
                                @else
                                    <span class="badge badge-success">Available</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                No class data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Enrollments -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Recent Enrollments</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Enrollment Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentEnrollments as $student)
                        <tr>
                            <td class="font-medium">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>{{ $student->class->name ?? 'N/A' }}</td>
                            <td>{{ $student->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">
                                No recent enrollments
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
