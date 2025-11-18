@extends('layouts.modern')

@section('title', 'Attendance')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Attendance Management</h1>
            <p class="text-gray-600 mt-1">Mark and track student attendance</p>
        </div>
        <div class="flex space-x-3">
            <select class="form-select">
                <option>Select Class</option>
                <option>Class 10-A</option>
                <option>Class 10-B</option>
            </select>
            <input type="date" class="form-input" value="{{ date('Y-m-d') }}">
            <button class="btn btn-primary">
                <i class="fas fa-clipboard-check mr-2"></i>
                Mark Attendance
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Today's Attendance</h2>
        </div>
        <div class="card-body">
            <p class="text-center text-gray-600 py-8">Attendance marking interface - UI ready</p>
        </div>
    </div>
</div>
@endsection
