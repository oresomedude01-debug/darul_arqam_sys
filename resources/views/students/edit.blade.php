@extends('layouts.app')

@section('title', 'Edit Student')

@section('breadcrumb')
    <span class="text-gray-400">Students</span>
    <span class="text-gray-400">/</span>
    <a href="{{ route('students.index') }}" class="text-gray-400 hover:text-gray-600">All Students</a>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">Edit Student</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Student</h1>
            <p class="text-gray-600 mt-1">Update student information</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    <!-- The form content is similar to create.blade.php but with @method('PUT') -->
    <div class="alert alert-info">
        <i class="fas fa-info-circle text-xl"></i>
        <p>Edit form is similar to create form. In a real application, this would be pre-populated with student data.</p>
    </div>
</div>
@endsection
