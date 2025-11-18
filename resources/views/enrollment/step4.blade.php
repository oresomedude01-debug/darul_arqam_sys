@extends('layouts.public')

@section('title', 'Parent/Guardian Information - Step 4 of 5')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-gray-700">Step 4 of 5</span>
            <span class="text-sm text-gray-600">80% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-primary-600 h-3 rounded-full transition-all duration-300" style="width: 80%"></div>
        </div>
    </div>

    <!-- Header -->
    <div class="card bg-gradient-to-r from-primary-50 to-blue-50 border-l-4 border-primary-500 mb-6">
        <div class="card-body">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-12 h-12 bg-primary-600 text-white rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Parent/Guardian Information</h2>
                    <p class="text-gray-700 text-sm">Final step - Please provide contact information</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('enrollment.process-step4') }}" method="POST">
        @csrf

        <!-- Primary Contact -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">
                    <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-sm mr-2">Primary Contact</span>
                </h3>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="parent1_name" class="form-input" value="{{ old('parent1_name', $data['parent1_name'] ?? '') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Relationship <span class="text-red-500">*</span></label>
                        <select name="parent1_relationship" class="form-select" required>
                            <option value="">Select Relationship</option>
                            @foreach(['Father', 'Mother', 'Guardian', 'Other'] as $rel)
                                <option value="{{ $rel }}" {{ old('parent1_relationship', $data['parent1_relationship'] ?? '') === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="parent1_phone" class="form-input" value="{{ old('parent1_phone', $data['parent1_phone'] ?? '') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="parent1_email" class="form-input" value="{{ old('parent1_email', $data['parent1_email'] ?? '') }}" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Occupation</label>
                        <input type="text" name="parent1_occupation" class="form-input" value="{{ old('parent1_occupation', $data['parent1_occupation'] ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Contact -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm mr-2">Secondary Contact (Optional)</span>
                </h3>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Full Name</label>
                        <input type="text" name="parent2_name" class="form-input" value="{{ old('parent2_name', $data['parent2_name'] ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label">Relationship</label>
                        <select name="parent2_relationship" class="form-select">
                            <option value="">Select Relationship</option>
                            @foreach(['Father', 'Mother', 'Guardian', 'Other'] as $rel)
                                <option value="{{ $rel }}" {{ old('parent2_relationship', $data['parent2_relationship'] ?? '') === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="parent2_phone" class="form-input" value="{{ old('parent2_phone', $data['parent2_phone'] ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label">Email Address</label>
                        <input type="email" name="parent2_email" class="form-input" value="{{ old('parent2_email', $data['parent2_email'] ?? '') }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Occupation</label>
                        <input type="text" name="parent2_occupation" class="form-input" value="{{ old('parent2_occupation', $data['parent2_occupation'] ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('enrollment.step3') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <button type="submit" class="btn btn-primary text-lg">
                <i class="fas fa-check mr-2"></i>Complete Enrollment
            </button>
        </div>
    </form>
</div>
@endsection
