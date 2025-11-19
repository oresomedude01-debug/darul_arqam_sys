@extends('layouts.spa')

@section('title', 'Students')

@section('breadcrumb')
    <span class="text-gray-400">Students</span>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">All Students</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success fade-in">
        <i class="fas fa-check-circle text-xl"></i>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Students Management</h1>
            <p class="text-gray-600 mt-1">Manage all registered students in your school</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('students.export') }}" class="btn btn-outline">
                <i class="fas fa-download mr-2"></i>
                Export
            </a>
            <a href="{{ route('students.import-form') }}" class="btn btn-outline">
                <i class="fas fa-upload mr-2"></i>
                Import
            </a>
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Add Student
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Male Students</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['male']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-between">
                        <i class="fas fa-male text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Female Students</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['female']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-female text-pink-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">New This Month</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['new_this_month']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-plus text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by name, ID, or email..."
                           class="form-input">
                </div>

                <!-- Class Filter -->
                <div>
                    <select name="class" class="form-select">
                        <option value="">All Classes</option>
                        @foreach(['Nursery 1', 'Nursery 2', 'Primary 1', 'Primary 2', 'Primary 3', 'Primary 4', 'Primary 5', 'Primary 6', 'JSS 1', 'JSS 2', 'JSS 3', 'SSS 1', 'SSS 2', 'SSS 3'] as $class)
                            <option value="{{ $class }}" {{ request('class') === $class ? 'selected' : '' }}>
                                {{ $class }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gender Filter -->
                <div>
                    <select name="gender" class="form-select">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="graduated" {{ request('status') === 'graduated' ? 'selected' : '' }}>Graduated</option>
                        <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="md:col-span-5 flex space-x-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-outline">
                        <i class="fas fa-redo mr-2"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">
                All Students
                @if($students->total() > 0)
                    <span class="text-gray-500 font-normal">({{ number_format($students->total()) }})</span>
                @endif
            </h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Show:</span>
                <select class="form-select text-sm py-1" onchange="window.location.href=this.value">
                    <option value="?per_page=10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="?per_page=20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                    <option value="?per_page=50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="?per_page=100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Gender</th>
                            <th>Admission Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td class="font-medium text-primary-600">
                                <a href="{{ route('students.show', $student->id) }}" class="hover:underline">
                                    {{ $student->admission_number }}
                                </a>
                            </td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="avatar avatar-sm {{ $student->gender === 'male' ? 'bg-blue-500' : 'bg-pink-500' }}">
                                        @if($student->photo_path)
                                            <img src="{{ Storage::url($student->photo_path) }}" alt="{{ $student->full_name }}" class="w-full h-full rounded-full object-cover">
                                        @else
                                            <span>{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $student->full_name }}</p>
                                        @if($student->email)
                                            <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($student->class_level)
                                    <span class="badge badge-primary">{{ $student->class_level }}</span>
                                    @if($student->section)
                                        <span class="text-xs text-gray-500">Section {{ $student->section }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="capitalize">{{ $student->gender }}</span>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <div class="text-gray-900">{{ $student->admission_date->format('M d, Y') }}</div>
                                    <div class="text-gray-500 text-xs">{{ $student->admission_date->diffForHumans() }}</div>
                                </div>
                            </td>
                            <td>
                                @switch($student->status)
                                    @case('active')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i> Active
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                        @break
                                    @case('graduated')
                                        <span class="badge badge-info">
                                            <i class="fas fa-graduation-cap mr-1"></i> Graduated
                                        </span>
                                        @break
                                    @case('withdrawn')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle mr-1"></i> Withdrawn
                                        </span>
                                        @break
                                    @default
                                        <span class="badge badge-secondary">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                @endswitch
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('students.show', $student->id) }}"
                                       class="text-blue-600 hover:text-blue-700"
                                       data-tooltip="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student->id) }}"
                                       class="text-green-600 hover:text-green-700"
                                       data-tooltip="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('students.destroy', $student->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this student?')"
                                                class="text-red-600 hover:text-red-700"
                                                data-tooltip="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8">
                                <div class="text-gray-400">
                                    <i class="fas fa-user-graduate text-4xl mb-3"></i>
                                    <p class="text-lg">No students found</p>
                                    @if(request()->hasAny(['search', 'class', 'gender', 'status']))
                                        <p class="text-sm mt-2">Try adjusting your filters</p>
                                        <a href="{{ route('students.index') }}" class="text-primary-600 hover:text-primary-700 mt-2 inline-block">
                                            Clear all filters
                                        </a>
                                    @else
                                        <a href="{{ route('students.create') }}" class="text-primary-600 hover:text-primary-700 mt-2 inline-block">
                                            Add your first student
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($students->hasPages())
        <div class="card-footer">
            <div class="pagination">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $students->firstItem() }}</span> to
                        <span class="font-medium">{{ $students->lastItem() }}</span> of
                        <span class="font-medium">{{ number_format($students->total()) }}</span> results
                    </p>
                </div>
                <div class="flex space-x-2">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
