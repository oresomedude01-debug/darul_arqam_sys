@extends('layouts.public')

@section('title', 'Previous School - Step 2 of 5')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-gray-700">Step 2 of 5</span>
            <span class="text-sm text-gray-600">40% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-primary-600 h-3 rounded-full transition-all duration-300" style="width: 40%"></div>
        </div>
    </div>

    <!-- Header -->
    <div class="card bg-gradient-to-r from-primary-50 to-blue-50 border-l-4 border-primary-500 mb-6">
        <div class="card-body">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-12 h-12 bg-primary-600 text-white rounded-full flex items-center justify-center">
                    <i class="fas fa-school text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Previous School Information</h2>
                    <p class="text-gray-700 text-sm">If the student attended another school, please provide details</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('enrollment.process-step2') }}" method="POST">
        @csrf

        <div class="card mb-6">
            <div class="card-body space-y-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-900">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Optional:</strong> If this is the student's first school enrollment, you can skip this step.
                    </p>
                </div>

                <div>
                    <label class="form-label">Previous School Name</label>
                    <input type="text" name="previous_school_name" class="form-input" value="{{ old('previous_school_name', $data['previous_school_name'] ?? '') }}">
                </div>

                <div>
                    <label class="form-label">School Address</label>
                    <textarea name="previous_school_address" class="form-textarea" rows="2">{{ old('previous_school_address', $data['previous_school_address'] ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Last Grade/Class Attended</label>
                        <input type="text" name="previous_school_grade" class="form-input" value="{{ old('previous_school_grade', $data['previous_school_grade'] ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label">Year Left</label>
                        <input type="number" name="previous_school_year" class="form-input" min="2000" max="{{ date('Y') }}" value="{{ old('previous_school_year', $data['previous_school_year'] ?? '') }}">
                    </div>
                </div>

                <div>
                    <label class="form-label">Reason for Leaving</label>
                    <textarea name="previous_school_reason" class="form-textarea" rows="2" placeholder="E.g., Relocation, seeking better opportunities, etc.">{{ old('previous_school_reason', $data['previous_school_reason'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('enrollment.step1') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <button type="submit" class="btn btn-primary">
                Next Step <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </form>
</div>
@endsection
