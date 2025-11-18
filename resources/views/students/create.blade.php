@extends('layouts.app')

@section('title', 'Add Student')

@section('breadcrumb')
    <span class="text-gray-400">Students</span>
    <span class="text-gray-400">/</span>
    <a href="{{ route('students.index') }}" class="text-gray-400 hover:text-gray-600">All Students</a>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">Add New</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Student</h1>
            <p class="text-gray-600 mt-1">Register a new student in the system</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" data-validate>
        @csrf

        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-user mr-2 text-primary-600"></i>
                    Personal Information
                </h2>
            </div>
            <div class="card-body space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Profile Photo -->
                    <div class="md:col-span-3">
                        <label class="form-label">Profile Photo</label>
                        <div class="file-upload" x-data="{ preview: null }">
                            <input type="file"
                                   name="photo"
                                   accept="image/*"
                                   class="hidden"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 rounded-full bg-gray-200 mb-4 overflow-hidden">
                                    <img x-show="preview"
                                         :src="preview"
                                         alt="Preview"
                                         class="w-full h-full object-cover">
                                    <div x-show="!preview" class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400 text-3xl"></i>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-upload mr-2"></i>
                                    Click or drag to upload photo
                                </p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- First Name -->
                    <div>
                        <label class="form-label">First Name <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="first_name"
                               class="form-input"
                               placeholder="John"
                               required>
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label class="form-label">Middle Name</label>
                        <input type="text"
                               name="middle_name"
                               class="form-input"
                               placeholder="Michael">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="form-label">Last Name <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="last_name"
                               class="form-input"
                               placeholder="Doe"
                               required>
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label class="form-label">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date"
                               name="date_of_birth"
                               class="form-input"
                               required>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="form-label">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" class="form-select" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <!-- Blood Group -->
                    <div>
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="card mt-6">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-graduation-cap mr-2 text-primary-600"></i>
                    Academic Information
                </h2>
            </div>
            <div class="card-body space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Admission Number -->
                    <div>
                        <label class="form-label">Admission Number <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="admission_number"
                               class="form-input"
                               placeholder="STU-2025-001"
                               required>
                    </div>

                    <!-- Admission Date -->
                    <div>
                        <label class="form-label">Admission Date <span class="text-red-500">*</span></label>
                        <input type="date"
                               name="admission_date"
                               class="form-input"
                               required>
                    </div>

                    <!-- Class -->
                    <div>
                        <label class="form-label">Class <span class="text-red-500">*</span></label>
                        <select name="class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            <option value="1">Class 10-A</option>
                            <option value="2">Class 10-B</option>
                            <option value="3">Class 11-A</option>
                            <option value="4">Class 11-B</option>
                            <option value="5">Class 12-A</option>
                            <option value="6">Class 12-B</option>
                        </select>
                    </div>

                    <!-- Roll Number -->
                    <div>
                        <label class="form-label">Roll Number</label>
                        <input type="text"
                               name="roll_number"
                               class="form-input"
                               placeholder="15">
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mt-6">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-address-book mr-2 text-primary-600"></i>
                    Contact Information
                </h2>
            </div>
            <div class="card-body space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div>
                        <label class="form-label">Email Address</label>
                        <input type="email"
                               name="email"
                               class="form-input"
                               placeholder="student@email.com">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input type="tel"
                               name="phone"
                               class="form-input"
                               placeholder="+1 (555) 123-4567">
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="form-label">Address</label>
                        <textarea name="address"
                                  class="form-textarea"
                                  rows="3"
                                  placeholder="123 Main Street, City, State, ZIP"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parent/Guardian Information -->
        <div class="card mt-6">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-users mr-2 text-primary-600"></i>
                    Parent/Guardian Information
                </h2>
            </div>
            <div class="card-body space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Father's Name -->
                    <div>
                        <label class="form-label">Father's Name</label>
                        <input type="text"
                               name="father_name"
                               class="form-input"
                               placeholder="Robert Doe">
                    </div>

                    <!-- Father's Phone -->
                    <div>
                        <label class="form-label">Father's Phone</label>
                        <input type="tel"
                               name="father_phone"
                               class="form-input"
                               placeholder="+1 (555) 123-4567">
                    </div>

                    <!-- Mother's Name -->
                    <div>
                        <label class="form-label">Mother's Name</label>
                        <input type="text"
                               name="mother_name"
                               class="form-input"
                               placeholder="Mary Doe">
                    </div>

                    <!-- Mother's Phone -->
                    <div>
                        <label class="form-label">Mother's Phone</label>
                        <input type="tel"
                               name="mother_phone"
                               class="form-input"
                               placeholder="+1 (555) 234-5678">
                    </div>

                    <!-- Guardian Email -->
                    <div>
                        <label class="form-label">Guardian Email</label>
                        <input type="email"
                               name="guardian_email"
                               class="form-input"
                               placeholder="guardian@email.com">
                    </div>

                    <!-- Emergency Contact -->
                    <div>
                        <label class="form-label">Emergency Contact</label>
                        <input type="tel"
                               name="emergency_contact"
                               class="form-input"
                               placeholder="+1 (555) 345-6789">
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card mt-6">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-info-circle mr-2 text-primary-600"></i>
                    Additional Information
                </h2>
            </div>
            <div class="card-body space-y-6">
                <!-- Notes -->
                <div>
                    <label class="form-label">Notes</label>
                    <textarea name="notes"
                              class="form-textarea"
                              rows="4"
                              placeholder="Any additional information about the student..."></textarea>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 mt-6">
            <a href="{{ route('students.index') }}" class="btn btn-outline">
                Cancel
            </a>
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-redo mr-2"></i>
                Reset
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>
                Save Student
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Form specific scripts
    console.log('Student create form loaded');

    // Auto-generate admission number if needed
    document.addEventListener('DOMContentLoaded', function() {
        const admissionInput = document.querySelector('input[name="admission_number"]');
        if (admissionInput && !admissionInput.value) {
            // You can implement auto-generation logic here
        }
    });
</script>
@endpush
@endsection
