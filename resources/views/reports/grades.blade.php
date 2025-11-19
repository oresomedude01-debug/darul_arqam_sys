@extends('layouts.spa')

@section('title', 'Grade Reports')

@section('breadcrumb')
    <a href="{{ route('reports.index') }}" class="text-gray-600 hover:text-gray-900">Reports</a>
    <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
    <span class="font-semibold text-gray-900">Grade Reports</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Grade Reports</h1>
            <p class="text-gray-600 mt-1">Class performance and grade distribution analysis</p>
        </div>
        <button class="btn btn-primary">
            <i class="fas fa-file-export mr-2"></i>
            Export Report
        </button>
    </div>

    <!-- Grade Distribution -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Grade Distribution</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach($gradeDistribution as $grade => $count)
                <div class="text-center p-4 bg-gray-50 rounded-lg border-2 border-gray-200 hover:border-primary-500 transition-colors">
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $count }}</div>
                    <div class="text-sm text-gray-600">{{ $grade }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Class Performance Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Class Performance Analysis</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Students</th>
                            <th>Average Grade</th>
                            <th>Highest Grade</th>
                            <th>Lowest Grade</th>
                            <th>Passing</th>
                            <th>Failing</th>
                            <th>Total Grades</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classGrades as $class)
                        <tr>
                            <td class="font-medium">{{ $class['class_name'] }}</td>
                            <td>{{ $class['student_count'] }}</td>
                            <td>
                                <span class="font-bold text-{{ $class['avg_grade'] >= 80 ? 'green' : ($class['avg_grade'] >= 70 ? 'blue' : 'yellow') }}-600">
                                    {{ $class['avg_grade'] }}%
                                </span>
                            </td>
                            <td><span class="badge badge-success">{{ $class['highest_grade'] }}%</span></td>
                            <td><span class="badge badge-danger">{{ $class['lowest_grade'] }}%</span></td>
                            <td><span class="badge badge-primary">{{ $class['passing_count'] }}</span></td>
                            <td><span class="badge badge-warning">{{ $class['failing_count'] }}</span></td>
                            <td>{{ $class['total_grades'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                No grade data available
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
