@extends('layouts.public')

@section('title', 'Enter Registration Token')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Welcome Card -->
    <div class="card bg-gradient-to-r from-primary-50 to-blue-50 border-l-4 border-primary-500 mb-8">
        <div class="card-body">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-graduation-cap text-primary-600 text-4xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome to Darul Arqam School</h2>
                    <p class="text-gray-700 mb-3">Thank you for choosing us for your child's education. Let's begin the enrollment process.</p>
                    <div class="bg-white/50 rounded-lg p-3 mt-4">
                        <p class="text-sm font-semibold text-gray-900 mb-2"><i class="fas fa-info-circle mr-2"></i>What you'll need:</p>
                        <ul class="text-sm text-gray-700 space-y-1 ml-6">
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Valid registration token</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Student's birth certificate or identification</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Student's photo (optional)</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Previous school information (if applicable)</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Parent/Guardian contact information</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Token Entry Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-ticket-alt mr-2 text-primary-600"></i>
                Enter Your Registration Token
            </h3>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-600 mr-3 mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-red-900">Error</p>
                            <ul class="text-sm text-red-700 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('enrollment.validate-token') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="form-label">Registration Token <span class="text-red-500">*</span></label>
                    <input type="text"
                           name="token_code"
                           class="form-input text-lg font-mono tracking-wider"
                           placeholder="DAREG-XXXXXX-XXXXX"
                           value="{{ old('token_code') }}"
                           required
                           autofocus>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                        Enter the registration token provided by the school administration
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-900 font-semibold mb-2">
                        <i class="fas fa-shield-alt mr-2"></i>Don't have a token?
                    </p>
                    <p class="text-sm text-blue-800">
                        Please contact the school administration at <strong>+234 XXX XXX XXXX</strong> or email
                        <strong>admissions@darularqam.edu</strong> to request a registration token.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary w-full text-lg py-3">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Continue to Enrollment
                </button>
            </form>
        </div>
    </div>

    <!-- Steps Overview -->
    <div class="card mt-8">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list-ol mr-2 text-primary-600"></i>
                Enrollment Steps
            </h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center font-bold text-sm">
                        1
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Token Validation</p>
                        <p class="text-sm text-gray-600">Enter your registration token</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-gray-100 text-gray-700 rounded-full flex items-center justify-center font-bold text-sm">
                        2
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Student Information</p>
                        <p class="text-sm text-gray-600">Basic details and photo</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-gray-100 text-gray-700 rounded-full flex items-center justify-center font-bold text-sm">
                        3
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Previous School</p>
                        <p class="text-sm text-gray-600">Educational background</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-gray-100 text-gray-700 rounded-full flex items-center justify-center font-bold text-sm">
                        4
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Health Information</p>
                        <p class="text-sm text-gray-600">Medical details and allergies</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-gray-100 text-gray-700 rounded-full flex items-center justify-center font-bold text-sm">
                        5
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Parent/Guardian</p>
                        <p class="text-sm text-gray-600">Contact information</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold text-sm">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Complete</p>
                        <p class="text-sm text-gray-600">Receive admission number</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
