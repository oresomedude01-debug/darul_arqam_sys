@extends('layouts.modern')

@section('title', $class->full_name . ' - Class Profile')

@section('breadcrumb')
    <span class="text-gray-400">Classes</span>
    <span class="text-gray-400">/</span>
    <a href="{{ route('classes.index') }}" class="text-primary-600 hover:text-primary-700">All Classes</a>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">{{ $class->full_name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                <!-- Class Info -->
                <div class="flex-1">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                            {{ substr($class->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $class->full_name }}</h1>
                            <p class="text-gray-600 mt-1">Class Code: <span class="font-mono font-semibold">{{ $class->class_code }}</span></p>
                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                @if($class->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($class->status === 'archived')
                                    <span class="badge badge-secondary">Archived</span>
                                @else
                                    <span class="badge badge-warning">Inactive</span>
                                @endif

                                @if($class->academic_year)
                                    <span class="badge badge-info">{{ $class->academic_year }}</span>
                                @endif

                                @if($class->room_number)
                                    <span class="badge badge-primary">
                                        <i class="fas fa-door-open mr-1"></i>{{ $class->room_number }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('classes.edit', $class) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('classes.destroy', $class) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this class?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Enrollment Overview -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-users mr-2 text-primary-600"></i>Enrollment Overview
                    </h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <!-- Enrollment Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm font-semibold text-blue-600 mb-1">Total Capacity</p>
                                <p class="text-3xl font-bold text-blue-700">{{ $class->capacity }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm font-semibold text-green-600 mb-1">Current Students</p>
                                <p class="text-3xl font-bold text-green-700">{{ $class->current_enrollment }}</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <p class="text-sm font-semibold text-purple-600 mb-1">Available Seats</p>
                                <p class="text-3xl font-bold text-purple-700">{{ $class->available_seats }}</p>
                            </div>
                        </div>

                        <!-- Enrollment Progress -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">Enrollment Progress</span>
                                <span class="text-sm font-semibold {{ $class->is_full ? 'text-red-600' : 'text-gray-600' }}">
                                    {{ $class->enrollment_percentage }}%
                                    @if($class->is_full)
                                        (Full)
                                    @endif
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div
                                    class="h-4 rounded-full transition-all duration-300 {{ $class->is_full ? 'bg-red-500' : ($class->enrollment_percentage >= 80 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                    style="width: {{ min($class->enrollment_percentage, 100) }}%"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Class Teacher -->
            @if($class->classTeacher)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chalkboard-teacher mr-2 text-primary-600"></i>Class Teacher
                    </h3>
                </div>
                <div class="card-body">
                    <div class="flex items-center space-x-4">
                        @if($class->classTeacher->profile_picture)
                            <img src="{{ asset('storage/' . $class->classTeacher->profile_picture) }}" alt="{{ $class->classTeacher->full_name }}" class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($class->classTeacher->first_name, 0, 1) }}{{ substr($class->classTeacher->last_name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-lg">{{ $class->classTeacher->full_name }}</h4>
                            <p class="text-gray-600 text-sm">
                                <i class="fas fa-envelope mr-2"></i>{{ $class->classTeacher->email }}
                            </p>
                            <p class="text-gray-600 text-sm">
                                <i class="fas fa-phone mr-2"></i>{{ $class->classTeacher->phone }}
                            </p>
                        </div>
                        <a href="{{ route('teachers.show', $class->classTeacher) }}" class="btn btn-sm btn-outline">
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="card border-dashed">
                <div class="card-body text-center py-8">
                    <i class="fas fa-user-slash text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-600">No class teacher assigned</p>
                    <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-primary mt-3">
                        <i class="fas fa-plus mr-1"></i>Assign Teacher
                    </a>
                </div>
            </div>
            @endif

            <!-- Students List -->
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-user-graduate mr-2 text-primary-600"></i>Students ({{ $class->students->count() }})
                    </h3>
                    <a href="{{ route('students.index', ['class_level' => $class->name, 'section' => $class->section]) }}" class="btn btn-sm btn-outline">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($class->students->count() > 0)
                        <div class="space-y-3">
                            @foreach($class->students->take(5) as $student)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold">
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $student->full_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $student->admission_number }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('students.show', $student) }}" class="btn btn-xs btn-outline">
                                    View
                                </a>
                            </div>
                            @endforeach

                            @if($class->students->count() > 5)
                            <div class="text-center pt-2">
                                <a href="{{ route('students.index', ['class_level' => $class->name, 'section' => $class->section]) }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold">
                                    View all {{ $class->students->count() }} students →
                                </a>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-user-graduate text-4xl mb-3 text-gray-300"></i>
                            <p>No students enrolled yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            @if($class->description)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-sticky-note mr-2 text-primary-600"></i>Description
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $class->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card bg-gradient-to-br from-primary-50 to-blue-50">
                <div class="card-body">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Quick Stats</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 text-sm">Students</span>
                            <span class="text-2xl font-bold text-primary-600">{{ $class->current_enrollment }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 text-sm">Capacity</span>
                            <span class="text-2xl font-bold text-primary-600">{{ $class->capacity }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 text-sm">Utilization</span>
                            <span class="text-2xl font-bold text-primary-600">{{ $class->enrollment_percentage }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 text-sm">Status</span>
                            @if($class->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($class->status === 'archived')
                                <span class="badge badge-secondary">Archived</span>
                            @else
                                <span class="badge badge-warning">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Class Schedule -->
            @if($class->start_time || $class->end_time)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-clock mr-2 text-primary-600"></i>Schedule
                    </h3>
                </div>
                <div class="card-body space-y-3">
                    @if($class->start_time)
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Start Time</p>
                        <p class="text-gray-900">{{ $class->start_time->format('g:i A') }}</p>
                    </div>
                    @endif

                    @if($class->end_time)
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">End Time</p>
                        <p class="text-gray-900">{{ $class->end_time->format('g:i A') }}</p>
                    </div>
                    @endif

                    @if($class->start_time && $class->end_time)
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-sm font-semibold text-gray-600 mb-1">Duration</p>
                        <p class="text-gray-900">
                            {{ $class->start_time->diff($class->end_time)->format('%h hours %i minutes') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- System Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-database mr-2 text-primary-600"></i>System Information
                    </h3>
                </div>
                <div class="card-body space-y-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Created</p>
                        <p class="text-gray-900 text-sm">{{ $class->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Last Updated</p>
                        <p class="text-gray-900 text-sm">{{ $class->updated_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('classes.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i>Back to Classes List
        </a>
    </div>
</div>
@endsection
