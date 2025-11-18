@extends('layouts.app')

@section('title', 'Teachers')

@section('breadcrumb')
    <span class="text-gray-400">Teachers</span>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">All Teachers</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Teachers Management</h1>
            <p class="text-gray-600 mt-1">Manage all teachers in your school</p>
        </div>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Add Teacher
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="text-center text-gray-600 py-8">Teachers list view - UI ready for backend integration</p>
        </div>
    </div>
</div>
@endsection
