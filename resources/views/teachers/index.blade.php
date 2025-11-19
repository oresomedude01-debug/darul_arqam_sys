@extends('layouts.spa')

@section('title', 'Teachers Directory')

@section('breadcrumb')
    <span class="text-gray-400">Teachers</span>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">All Teachers</span>
@endsection

@section('content')
<div x-data="teachersPage()" class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Teachers Directory</h1>
            <p class="text-sm text-gray-600 mt-1">Manage your teaching staff</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('teachers.export', request()->query()) }}" class="btn btn-outline">
                <i class="fas fa-download mr-2"></i>Export CSV
            </a>
            <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>Add Teacher
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Teachers -->
        <div class="card hover:shadow-lg transition-all duration-200">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Total Teachers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Teachers -->
        <div class="card hover:shadow-lg transition-all duration-200">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Active</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ number_format($stats['active']) }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-check text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inactive Teachers -->
        <div class="card hover:shadow-lg transition-all duration-200">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Inactive</p>
                        <p class="text-3xl font-bold text-gray-600 mt-1">{{ number_format($stats['inactive']) }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-times text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- On Leave -->
        <div class="card hover:shadow-lg transition-all duration-200">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">On Leave</p>
                        <p class="text-3xl font-bold text-orange-600 mt-1">{{ number_format($stats['on_leave']) }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-times text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Search & Filters</h3>
                <button
                    @click="showFilters = !showFilters"
                    class="btn btn-sm btn-outline lg:hidden"
                >
                    <i class="fas fa-filter mr-2"></i>
                    <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                </button>
            </div>

            <form method="GET" action="{{ route('teachers.index') }}" class="space-y-4">
                <!-- Search Bar -->
                <div class="form-group">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name, employee ID, email, or phone..."
                            class="form-input pl-10"
                        >
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Filter Options -->
                <div
                    x-show="showFilters"
                    x-transition
                    class="grid grid-cols-1 md:grid-cols-4 gap-4"
                >
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">All Genders</option>
                            <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subject</label>
                        <input
                            type="text"
                            name="subject"
                            value="{{ request('subject') }}"
                            placeholder="e.g., Mathematics"
                            class="form-input"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Class</label>
                        <input
                            type="text"
                            name="class"
                            value="{{ request('class') }}"
                            placeholder="e.g., JSS 1"
                            class="form-input"
                        >
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline">
                        <i class="fas fa-times mr-2"></i>Clear Filters
                    </a>
                    <button
                        type="button"
                        @click="toggleView()"
                        class="btn btn-outline ml-auto hidden md:inline-flex"
                    >
                        <i :class="viewMode === 'table' ? 'fas fa-th' : 'fas fa-table'" class="mr-2"></i>
                        <span x-text="viewMode === 'table' ? 'Grid View' : 'Table View'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Teachers List -->
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">
                Teachers ({{ $teachers->total() }})
            </h3>
            <div class="text-sm text-gray-600">
                Showing {{ $teachers->firstItem() ?? 0 }} to {{ $teachers->lastItem() ?? 0 }} of {{ $teachers->total() }}
            </div>
        </div>

        <!-- Desktop Table View -->
        <div x-show="viewMode === 'table'" class="hidden md:block overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ route('teachers.index', array_merge(request()->query(), ['sort' => 'employee_id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable">
                                Employee ID
                                @if(request('sort') === 'employee_id')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('teachers.index', array_merge(request()->query(), ['sort' => 'first_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable">
                                Teacher Info
                                @if(request('sort') === 'first_name')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th>Subjects</th>
                        <th>Classes</th>
                        <th>
                            <a href="{{ route('teachers.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable">
                                Status
                                @if(request('sort') === 'status')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="font-mono text-sm">{{ $teacher->employee_id }}</td>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($teacher->profile_picture)
                                        <img src="{{ asset('storage/' . $teacher->profile_picture) }}" alt="{{ $teacher->full_name }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-semibold">
                                            {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $teacher->full_name }}</div>
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-envelope mr-1"></i>{{ $teacher->email }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-1"></i>{{ $teacher->phone }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($teacher->subjects && count($teacher->subjects) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($teacher->subjects, 0, 2) as $subject)
                                        <span class="badge badge-info">{{ $subject }}</span>
                                    @endforeach
                                    @if(count($teacher->subjects) > 2)
                                        <span class="badge badge-secondary">+{{ count($teacher->subjects) - 2 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">No subjects</span>
                            @endif
                        </td>
                        <td>
                            @if($teacher->classes && count($teacher->classes) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($teacher->classes, 0, 2) as $class)
                                        <span class="badge badge-primary">{{ $class }}</span>
                                    @endforeach
                                    @if(count($teacher->classes) > 2)
                                        <span class="badge badge-secondary">+{{ count($teacher->classes) - 2 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">No classes</span>
                            @endif
                        </td>
                        <td>
                            @if($teacher->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($teacher->status === 'on_leave')
                                <span class="badge badge-warning">On Leave</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm btn-outline" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('teachers.assign', $teacher) }}" class="btn btn-sm btn-info" title="Assign">
                                    <i class="fas fa-tasks"></i>
                                </a>
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fas fa-chalkboard-teacher text-6xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-semibold">No teachers found</p>
                                <p class="text-sm mt-1">Try adjusting your search or filters</p>
                                <a href="{{ route('teachers.create') }}" class="btn btn-primary mt-4">
                                    <i class="fas fa-plus mr-2"></i>Add First Teacher
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div x-show="viewMode === 'grid' || window.innerWidth < 768" class="md:hidden p-4 space-y-4">
            @forelse($teachers as $teacher)
            <div class="table-card hover:shadow-lg transition-all duration-200">
                <div class="flex items-start space-x-4">
                    <!-- Profile Picture -->
                    <div class="flex-shrink-0">
                        @if($teacher->profile_picture)
                            <img src="{{ asset('storage/' . $teacher->profile_picture) }}" alt="{{ $teacher->full_name }}" class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">{{ $teacher->full_name }}</h4>
                                <p class="text-sm text-gray-600 font-mono">{{ $teacher->employee_id }}</p>
                            </div>
                            @if($teacher->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($teacher->status === 'on_leave')
                                <span class="badge badge-warning">On Leave</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </div>

                        <div class="mt-3 space-y-2">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>{{ $teacher->email }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>{{ $teacher->phone }}
                            </p>

                            @if($teacher->subjects && count($teacher->subjects) > 0)
                            <div>
                                <p class="text-xs text-gray-500 font-semibold mb-1">Subjects:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($teacher->subjects as $subject)
                                        <span class="badge badge-info text-xs">{{ $subject }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($teacher->classes && count($teacher->classes) > 0)
                            <div>
                                <p class="text-xs text-gray-500 font-semibold mb-1">Classes:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($teacher->classes as $class)
                                        <span class="badge badge-primary text-xs">{{ $class }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm btn-outline flex-1">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            <a href="{{ route('teachers.assign', $teacher) }}" class="btn btn-sm btn-info flex-1">
                                <i class="fas fa-tasks mr-1"></i>Assign
                            </a>
                            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-primary flex-1">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="flex flex-col items-center justify-center text-gray-500">
                    <i class="fas fa-chalkboard-teacher text-6xl mb-4 text-gray-300"></i>
                    <p class="text-lg font-semibold">No teachers found</p>
                    <p class="text-sm mt-1">Try adjusting your search or filters</p>
                    <a href="{{ route('teachers.create') }}" class="btn btn-primary mt-4">
                        <i class="fas fa-plus mr-2"></i>Add First Teacher
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($teachers->hasPages())
        <div class="card-footer">
            {{ $teachers->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function teachersPage() {
        return {
            viewMode: window.innerWidth >= 768 ? 'table' : 'grid',
            showFilters: window.innerWidth >= 1024,

            toggleView() {
                this.viewMode = this.viewMode === 'table' ? 'grid' : 'table';
            },

            init() {
                // Adjust view mode on resize
                window.addEventListener('resize', () => {
                    if (window.innerWidth < 768) {
                        this.viewMode = 'grid';
                    }
                });
            }
        }
    }
</script>
@endsection
