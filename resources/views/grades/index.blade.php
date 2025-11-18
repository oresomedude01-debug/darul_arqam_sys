@extends('layouts.modern')

@section('title', 'Grades & Results')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Grades & Results</h1>
            <p class="text-gray-600 mt-1">Manage student grades and examination results</p>
        </div>
        <button class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Add Grades
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-body">
                <p class="text-sm text-gray-600">Average Grade</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">85.5%</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="text-sm text-gray-600">Pass Rate</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">94.2%</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="text-sm text-gray-600">Top Performers</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">23</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="text-sm text-gray-600">Needs Attention</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">12</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Recent Grades</h2>
        </div>
        <div class="card-body">
            <p class="text-center text-gray-600 py-8">Grades management interface - UI ready</p>
        </div>
    </div>
</div>
@endsection
