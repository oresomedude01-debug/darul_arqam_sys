@extends('layouts.app')

@section('title', 'View Student')

@section('breadcrumb')
    <span class="text-gray-400">Students</span>
    <span class="text-gray-400">/</span>
    <a href="{{ route('students.index') }}" class="text-gray-400 hover:text-gray-600">All Students</a>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">View Details</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Student Details</h1>
            <p class="text-gray-600 mt-1">Complete information about the student</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('students.edit', $id) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('students.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="text-center text-gray-600 py-8">Student details view - UI ready for data display</p>
        </div>
    </div>
</div>
@endsection
