@extends('layouts.modern')

@section('title', $subject->name)

@section('breadcrumb')
    <span class="text-gray-400">Academics</span>
    <span class="text-gray-400">/</span>
    <a href="{{ route('subjects.index') }}" class="text-primary-600 hover:text-primary-700">Subjects</a>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">{{ $subject->name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                <!-- Subject Info -->
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 rounded-lg bg-{{ $subject->color }}-100 flex items-center justify-center">
                            <i class="fas fa-book text-{{ $subject->color }}-600 text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $subject->name }}</h1>
                            <p class="text-gray-600 mt-1">Code: <span class="font-mono font-semibold">{{ $subject->code }}</span></p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        @if($subject->category)
                            <span class="badge badge-secondary">{{ $subject->category }}</span>
                        @endif
                        @if($subject->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('subjects.index') }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this subject? This will remove it from all classes.')">
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
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            @if($subject->description)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-file-alt mr-2 text-primary-600"></i>Description
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-gray-700">{{ $subject->description }}</p>
                </div>
            </div>
            @endif

            <!-- Classes Teaching This Subject -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-school mr-2 text-primary-600"></i>Classes ({{ $subject->classes->count() }})
                    </h3>
                </div>
                <div class="card-body">
                    @if($subject->classes->count() > 0)
                    <div class="space-y-3">
                        @foreach($subject->classes as $class)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $class->full_name }}</h4>
                                            <div class="flex items-center gap-4 mt-1 text-sm text-gray-600">
                                                @if($class->classTeacher)
                                                    <span>
                                                        <i class="fas fa-user mr-1"></i>{{ $class->classTeacher->full_name }}
                                                    </span>
                                                @endif
                                                @php
                                                    $teacher = $class->pivot->teacher_id ? \App\Models\Teacher::find($class->pivot->teacher_id) : null;
                                                @endphp
                                                @if($teacher)
                                                    <span class="text-{{ $subject->color }}-600">
                                                        <i class="fas fa-chalkboard-teacher mr-1"></i>{{ $teacher->full_name }}
                                                    </span>
                                                @endif
                                                <span class="badge badge-{{ $subject->color }}">
                                                    {{ $class->pivot->periods_per_week }} periods/week
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('classes.show', $class) }}" class="btn btn-xs btn-outline">
                                    <i class="fas fa-external-link-alt mr-1"></i>View
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-school text-4xl text-gray-300 mb-3"></i>
                        <p>This subject is not assigned to any classes yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-bar mr-2 text-primary-600"></i>Statistics
                    </h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Classes</span>
                        <span class="text-2xl font-bold text-primary-600">{{ $subject->classes->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total Students</span>
                        <span class="text-2xl font-bold text-primary-600">
                            {{ $subject->classes->sum(function($class) { return $class->current_enrollment; }) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Status</span>
                        @if($subject->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Subject Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-info-circle mr-2 text-primary-600"></i>Details
                    </h3>
                </div>
                <div class="card-body space-y-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Subject Code</p>
                        <p class="text-gray-900 font-mono font-semibold">{{ $subject->code }}</p>
                    </div>

                    @if($subject->category)
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Category</p>
                        <p class="text-gray-900">{{ $subject->category }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Display Color</p>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded bg-{{ $subject->color }}-500"></div>
                            <span class="text-gray-900 capitalize">{{ $subject->color }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-database mr-2 text-primary-600"></i>System Info
                    </h3>
                </div>
                <div class="card-body space-y-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Created</p>
                        <p class="text-gray-900 text-sm">{{ $subject->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Last Updated</p>
                        <p class="text-gray-900 text-sm">{{ $subject->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
