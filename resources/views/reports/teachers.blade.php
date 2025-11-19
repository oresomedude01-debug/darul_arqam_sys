@extends('layouts.spa')

@section('title', 'Teacher Reports')

@section('breadcrumb')
    <a href="{{ route('reports.index') }}" class="text-gray-600 hover:text-gray-900">Reports</a>
    <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
    <span class="font-semibold text-gray-900">Teacher Reports</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Teacher Reports</h1>
            <p class="text-gray-600 mt-1">Teacher staffing and distribution statistics</p>
        </div>
        <button class="btn btn-primary">
            <i class="fas fa-file-export mr-2"></i>
            Export Report
        </button>
    </div>

    <!-- Teacher Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Teachers</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($totalTeachers) }}</h3>
                </div>
                <i class="fas fa-chalkboard-teacher text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Active</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($activeTeachers) }}</h3>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="stat-card from-red-500 to-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Inactive</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($inactiveTeachers) }}</h3>
                </div>
                <i class="fas fa-user-slash text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Teachers by Subject -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Teachers by Subject</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($teachersBySubject as $subject)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <i class="fas fa-book text-primary-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $subject['subject'] }}</p>
                            <p class="text-sm text-gray-600">{{ $subject['count'] }} {{ Str::plural('teacher', $subject['count']) }}</p>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $subject['count'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Hires -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Recent Hires</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Teacher Name</th>
                            <th>Title</th>
                            <th>Hire Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentHires as $teacher)
                        <tr>
                            <td class="font-medium">{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                            <td>{{ $teacher->title ?? 'Teacher' }}</td>
                            <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">
                                No recent hires
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
