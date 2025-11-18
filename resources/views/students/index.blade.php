@extends('layouts.app')

@section('title', 'Students')

@section('breadcrumb')
    <span class="text-gray-400">Students</span>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">All Students</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Students Management</h1>
            <p class="text-gray-600 mt-1">Manage all registered students in your school</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="btn btn-outline">
                <i class="fas fa-download mr-2"></i>
                Export
            </button>
            <button class="btn btn-outline">
                <i class="fas fa-upload mr-2"></i>
                Import
            </button>
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
                        <p class="text-2xl font-bold text-gray-900 mt-1">1,234</p>
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
                        <p class="text-2xl font-bold text-gray-900 mt-1">678</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
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
                        <p class="text-2xl font-bold text-gray-900 mt-1">556</p>
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
                        <p class="text-2xl font-bold text-gray-900 mt-1">24</p>
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4"
                 x-data="{ class: '', gender: '', status: '' }">
                <!-- Search -->
                <div class="md:col-span-2">
                    <input type="text"
                           placeholder="Search by name, ID, or email..."
                           class="form-input">
                </div>

                <!-- Class Filter -->
                <div>
                    <select x-model="class" class="form-select">
                        <option value="">All Classes</option>
                        <option value="10-A">Class 10-A</option>
                        <option value="10-B">Class 10-B</option>
                        <option value="11-A">Class 11-A</option>
                        <option value="11-B">Class 11-B</option>
                    </select>
                </div>

                <!-- Gender Filter -->
                <div>
                    <select x-model="gender" class="form-select">
                        <option value="">All Genders</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">All Students</h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Show:</span>
                <select class="form-select text-sm py-1">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-checkbox">
                            </th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Gender</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Student 1 -->
                        <tr>
                            <td>
                                <input type="checkbox" class="form-checkbox">
                            </td>
                            <td class="font-medium text-primary-600">STU-2025-001</td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="avatar avatar-sm bg-blue-500">
                                        <span>JD</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">John Doe</p>
                                        <p class="text-xs text-gray-500">john.doe@email.com</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-primary">10-A</span>
                            </td>
                            <td>Male</td>
                            <td>+1 (555) 123-4567</td>
                            <td>
                                <span class="badge badge-success">Active</span>
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('students.show', 1) }}"
                                       class="text-blue-600 hover:text-blue-700"
                                       data-tooltip="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', 1) }}"
                                       class="text-green-600 hover:text-green-700"
                                       data-tooltip="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete('John Doe', () => console.log('Delete'))"
                                            class="text-red-600 hover:text-red-700"
                                            data-tooltip="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Sample Student 2 -->
                        <tr>
                            <td>
                                <input type="checkbox" class="form-checkbox">
                            </td>
                            <td class="font-medium text-primary-600">STU-2025-002</td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="avatar avatar-sm bg-pink-500">
                                        <span>JS</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Jane Smith</p>
                                        <p class="text-xs text-gray-500">jane.smith@email.com</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-primary">10-A</span>
                            </td>
                            <td>Female</td>
                            <td>+1 (555) 234-5678</td>
                            <td>
                                <span class="badge badge-success">Active</span>
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('students.show', 2) }}"
                                       class="text-blue-600 hover:text-blue-700"
                                       data-tooltip="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', 2) }}"
                                       class="text-green-600 hover:text-green-700"
                                       data-tooltip="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete('Jane Smith', () => console.log('Delete'))"
                                            class="text-red-600 hover:text-red-700"
                                            data-tooltip="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Sample Student 3 -->
                        <tr>
                            <td>
                                <input type="checkbox" class="form-checkbox">
                            </td>
                            <td class="font-medium text-primary-600">STU-2025-003</td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="avatar avatar-sm bg-green-500">
                                        <span>MJ</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Michael Johnson</p>
                                        <p class="text-xs text-gray-500">michael.j@email.com</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-primary">10-B</span>
                            </td>
                            <td>Male</td>
                            <td>+1 (555) 345-6789</td>
                            <td>
                                <span class="badge badge-success">Active</span>
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('students.show', 3) }}"
                                       class="text-blue-600 hover:text-blue-700"
                                       data-tooltip="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', 3) }}"
                                       class="text-green-600 hover:text-green-700"
                                       data-tooltip="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete('Michael Johnson', () => console.log('Delete'))"
                                            class="text-red-600 hover:text-red-700"
                                            data-tooltip="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="pagination">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of{' '}
                        <span class="font-medium">1,234</span> results
                    </p>
                </div>
                <div class="flex space-x-2">
                    <button class="pagination-btn" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">2</button>
                    <button class="pagination-btn">3</button>
                    <span class="px-4 py-2">...</span>
                    <button class="pagination-btn">124</button>
                    <button class="pagination-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
