@extends('layouts.app')

@section('title', 'Classes')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Classes Management</h1>
            <p class="text-gray-600 mt-1">Manage all classes and subjects</p>
        </div>
        <a href="{{ route('classes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Add Class
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card hover:shadow-lg transition-shadow cursor-pointer">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-gray-900">Class 10-A</h3>
                <p class="text-gray-600 mt-2">42 Students</p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="badge badge-primary">Active</span>
                    <button class="text-primary-600 hover:text-primary-700">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
