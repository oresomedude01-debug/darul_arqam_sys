@extends('layouts.public')

@section('title', 'Enrollment Successful')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Success Card -->
    <div class="card bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 mb-8">
        <div class="card-body text-center py-12">
            <div class="w-20 h-20 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Enrollment Successful!</h1>
            <p class="text-lg text-gray-700 mb-6">Welcome to Darul Arqam School family</p>

            <div class="bg-white rounded-lg p-6 max-w-md mx-auto">
                <p class="text-sm text-gray-600 mb-2">Your Admission Number</p>
                <p class="text-3xl font-bold text-primary-600 tracking-wider font-mono">{{ $admissionNumber }}</p>
                <p class="text-xs text-gray-500 mt-2">Please keep this number for your records</p>
            </div>
        </div>
    </div>

    <!-- What's Next -->
    <div class="card mb-8">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list-check mr-2 text-primary-600"></i>
                What Happens Next?
            </h2>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center font-bold">
                        1
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Application Review</p>
                        <p class="text-sm text-gray-600">Our admissions team will review your application within 2-3 business days.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center font-bold">
                        2
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Email Confirmation</p>
                        <p class="text-sm text-gray-600">You will receive an email with further instructions and required documents.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center font-bold">
                        3
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Document Submission</p>
                        <p class="text-sm text-gray-600">Submit required documents at the school office or via email.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center font-bold">
                        4
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Final Approval</p>
                        <p class="text-sm text-gray-600">Once approved, you'll receive your official admission letter.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Documents -->
    <div class="card mb-8">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-file-alt mr-2 text-primary-600"></i>
                Required Documents
            </h2>
        </div>
        <div class="card-body">
            <p class="text-gray-700 mb-4">Please prepare the following documents for submission:</p>
            <ul class="space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                    <span class="text-gray-700">Birth Certificate or valid identification</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                    <span class="text-gray-700">Recent passport-sized photographs (2 copies)</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                    <span class="text-gray-700">Previous school report card (if applicable)</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                    <span class="text-gray-700">Proof of residence</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                    <span class="text-gray-700">Vaccination records</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="card mb-8">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-phone mr-2 text-primary-600"></i>
                Need Assistance?
            </h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="font-semibold text-gray-900 mb-2">Contact Us</p>
                    <p class="text-gray-700 text-sm">
                        <i class="fas fa-phone text-primary-600 mr-2"></i>+234 XXX XXX XXXX
                    </p>
                    <p class="text-gray-700 text-sm mt-1">
                        <i class="fas fa-envelope text-primary-600 mr-2"></i>admissions@darularqam.edu
                    </p>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 mb-2">Visit Us</p>
                    <p class="text-gray-700 text-sm">
                        <i class="fas fa-map-marker-alt text-primary-600 mr-2"></i>
                        School Address<br>
                        <span class="ml-6">City, State, ZIP</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="text-center space-y-4">
        <button onclick="window.print()" class="btn btn-outline">
            <i class="fas fa-print mr-2"></i>Print This Page
        </button>
        <div>
            <a href="{{ route('enrollment.token') }}" class="text-primary-600 hover:text-primary-700 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Enroll Another Student
            </a>
        </div>
    </div>
</div>
@endsection
