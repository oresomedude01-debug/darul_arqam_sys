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
<div x-data="{ activeTab: 'overview' }" class="space-y-6">
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
                    <a href="{{ route('classes.subjects.index', $class) }}" class="btn btn-info">
                        <i class="fas fa-book mr-2"></i>Assign Subjects
                    </a>
                    <a href="{{ route('classes.timetable.index', $class) }}" class="btn btn-success">
                        <i class="fas fa-calendar-alt mr-2"></i>Manage Timetable
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-4 px-6" aria-label="Tabs">
                <button
                    @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    <i class="fas fa-chart-pie mr-2"></i>Overview
                </button>
                <button
                    @click="activeTab = 'subjects'"
                    :class="activeTab === 'subjects' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    <i class="fas fa-book mr-2"></i>Subjects
                </button>
                <button
                    @click="activeTab = 'timetable'"
                    :class="activeTab === 'timetable' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    <i class="fas fa-calendar-alt mr-2"></i>Timetable
                </button>
                <button
                    @click="activeTab = 'students'"
                    :class="activeTab === 'students' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    <i class="fas fa-users mr-2"></i>Students <span class="ml-1 bg-gray-200 rounded-full px-2 py-0.5 text-xs">{{ $class->current_enrollment }}</span>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="card-body">
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" x-transition class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Enrollment Overview -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-users mr-2 text-primary-600"></i>Enrollment Overview
                            </h3>
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

                        <!-- Class Teacher -->
                        @if($class->classTeacher)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-chalkboard-teacher mr-2 text-primary-600"></i>Class Teacher
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
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
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <i class="fas fa-user-slash text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600">No class teacher assigned</p>
                            <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-primary mt-3">
                                <i class="fas fa-plus mr-1"></i>Assign Teacher
                            </a>
                        </div>
                        @endif

                        <!-- Description -->
                        @if($class->description)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-sticky-note mr-2 text-primary-600"></i>Description
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $class->description }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Right Column - Quick Stats -->
                    <div class="space-y-6">
                        <!-- Quick Stats -->
                        <div class="bg-gradient-to-br from-primary-50 to-blue-50 rounded-lg p-6">
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

                        <!-- Class Schedule -->
                        @if($class->start_time || $class->end_time)
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">
                                <i class="fas fa-clock mr-2 text-primary-600"></i>Schedule
                            </h4>
                            <div class="space-y-3">
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
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">
                                <i class="fas fa-database mr-2 text-primary-600"></i>System Info
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-600 mb-1">Created</p>
                                    <p class="text-gray-900 text-sm">{{ $class->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-600 mb-1">Last Updated</p>
                                    <p class="text-gray-900 text-sm">{{ $class->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects Tab -->
            <div x-show="activeTab === 'subjects'" x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-book mr-2 text-primary-600"></i>Assigned Subjects ({{ $class->subjects->count() }})
                    </h3>
                    <a href="{{ route('classes.subjects.index', $class) }}" class="btn btn-primary">
                        <i class="fas fa-cog mr-2"></i>Manage Subjects
                    </a>
                </div>

                <!-- Subjects Grid -->
                @if($class->subjects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($class->subjects as $subject)
                    <div class="bg-white border-l-4 border-{{ $subject->color }}-500 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $subject->name }}</h4>
                                @php
                                    $teacher = $subject->pivot->teacher_id ? \App\Models\Teacher::find($subject->pivot->teacher_id) : null;
                                @endphp
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $teacher ? $teacher->full_name : 'Not assigned' }}
                                </p>
                            </div>
                            <span class="badge badge-{{ $subject->color }}">{{ $subject->pivot->periods_per_week }} periods/week</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-clock mr-1"></i>{{ $subject->pivot->periods_per_week }} {{ Str::plural('period', $subject->pivot->periods_per_week) }} per week
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 mb-4">No subjects assigned yet</p>
                    <p class="text-sm text-gray-500 mb-4">Start by assigning subjects to this class</p>
                    <a href="{{ route('classes.subjects.index', $class) }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>Assign Subjects
                    </a>
                </div>
                @endif
            </div>

            <!-- Timetable Tab -->
            <div x-show="activeTab === 'timetable'" x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-calendar-alt mr-2 text-primary-600"></i>Weekly Timetable ({{ $class->timetables->count() }} periods)
                    </h3>
                    <a href="{{ route('classes.timetable.index', $class) }}" class="btn btn-primary">
                        <i class="fas fa-cog mr-2"></i>Manage Timetable
                    </a>
                </div>

                <!-- Timetable Grid -->
                @if($class->timetables->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-r border-gray-200">Time</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r border-gray-200">Monday</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r border-gray-200">Tuesday</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r border-gray-200">Wednesday</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r border-gray-200">Thursday</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Friday</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                // Group timetable entries by period number and day
                                $timetableGrid = [];
                                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

                                foreach($class->timetables as $entry) {
                                    $timeSlot = $entry->start_time->format('H:i') . ' - ' . $entry->end_time->format('H:i');
                                    if (!isset($timetableGrid[$timeSlot])) {
                                        $timetableGrid[$timeSlot] = [
                                            'period_number' => $entry->period_number,
                                            'entries' => []
                                        ];
                                    }
                                    $timetableGrid[$timeSlot]['entries'][$entry->day_of_week] = $entry;
                                }

                                // Sort by period number
                                uasort($timetableGrid, function($a, $b) {
                                    return $a['period_number'] <=> $b['period_number'];
                                });
                            @endphp

                            @foreach($timetableGrid as $timeSlot => $data)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r border-gray-200 whitespace-nowrap">{{ $timeSlot }}</td>
                                @foreach($days as $day)
                                    @php
                                        $entry = $data['entries'][$day] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center border-r border-gray-200 last:border-r-0">
                                        @if($entry)
                                            @if($entry->type === 'break' || $entry->type === 'lunch')
                                                <div class="bg-gray-100 rounded px-2 py-1 text-xs font-semibold text-gray-600 uppercase">{{ $entry->type }}</div>
                                            @elseif($entry->type === 'assembly')
                                                <div class="bg-purple-100 rounded px-2 py-1 text-xs font-semibold text-purple-600 uppercase">Assembly</div>
                                            @else
                                                <div class="bg-blue-50 border border-blue-200 rounded px-2 py-2 hover:bg-blue-100 cursor-pointer transition-colors">
                                                    <p class="text-sm font-semibold text-blue-900">{{ $entry->subject ? $entry->subject->name : 'No Subject' }}</p>
                                                    <p class="text-xs text-blue-600 mt-0.5">{{ $entry->teacher ? $entry->teacher->full_name : 'No Teacher' }}</p>
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-gray-400 text-xs">-</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 mb-4">No timetable created yet</p>
                    <p class="text-sm text-gray-500 mb-4">Start building the weekly schedule by adding periods</p>
                    <a href="{{ route('classes.timetable.index', $class) }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>Create Timetable
                    </a>
                </div>
                @endif

                @if($class->timetables->count() > 0)
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Timetable Overview</p>
                            <p class="text-sm text-blue-700 mt-1">This is a read-only view of the weekly timetable. Click "Manage Timetable" above to add, edit, or delete periods.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Students Tab -->
            <div x-show="activeTab === 'students'" x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-users mr-2 text-primary-600"></i>Enrolled Students ({{ $class->current_enrollment }})
                    </h3>
                    <a href="{{ route('students.index', ['class_level' => $class->name, 'section' => $class->section]) }}" class="btn btn-primary">
                        <i class="fas fa-external-link-alt mr-2"></i>View Full List
                    </a>
                </div>

                @if($class->students->count() > 0)
                <div class="space-y-3">
                    @foreach($class->students as $student)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $student->full_name }}</p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-mono">{{ $student->admission_number }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $student->email }}</span>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline">
                            View Profile
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-user-graduate text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">No students enrolled yet</p>
                </div>
                @endif
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
