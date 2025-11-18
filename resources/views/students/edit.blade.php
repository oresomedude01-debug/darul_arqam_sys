@extends('layouts.modern')

@section('title', 'Edit Student')

@section('breadcrumb')
    <span class="text-gray-400">Students</span>
    <span class="text-gray-400">/</span>
    <a href="{{ route('students.index') }}" class="text-gray-400 hover:text-gray-600">All Students</a>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">Edit Student</span>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="studentForm()">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Student</h1>
            <p class="text-gray-600 mt-1">Update student information for {{ $student->full_name }} ({{ $student->admission_number }})</p>
        </div>
        <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Profile
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-user mr-2 text-primary-600"></i>
                    Personal Information
                </h2>
            </div>
            <div class="card-body space-y-6">
                <!-- Profile Photo -->
                <div>
                    <label class="form-label">Profile Photo</label>
                    <div x-data="{ preview: '{{ $student->photo_url ? asset('storage/' . $student->photo_path) : '' }}' }" class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 rounded-full bg-gray-200 overflow-hidden">
                                <img x-show="preview"
                                     :src="preview"
                                     alt="Preview"
                                     class="w-full h-full object-cover">
                                <div x-show="!preview" class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-3xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file"
                                   name="photo"
                                   accept="image/*"
                                   id="photo-upload"
                                   class="hidden"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                            <label for="photo-upload" class="btn btn-outline cursor-pointer">
                                <i class="fas fa-upload mr-2"></i>
                                {{ $student->photo_path ? 'Change Photo' : 'Choose Photo' }}
                            </label>
                            <p class="text-xs text-gray-500 mt-2">PNG or JPG, maximum 2MB</p>
                        </div>
                    </div>
                    @error('photo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- First Name -->
                    <div>
                        <label class="form-label">First Name <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="first_name"
                               value="{{ old('first_name', $student->first_name) }}"
                               class="form-input"
                               placeholder="John"
                               required>
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label class="form-label">Middle Name</label>
                        <input type="text"
                               name="middle_name"
                               value="{{ old('middle_name', $student->middle_name) }}"
                               class="form-input"
                               placeholder="Michael">
                        @error('middle_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="form-label">Last Name <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="last_name"
                               value="{{ old('last_name', $student->last_name) }}"
                               class="form-input"
                               placeholder="Doe"
                               required>
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label class="form-label">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date"
                               name="date_of_birth"
                               value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}"
                               class="form-input"
                               max="{{ date('Y-m-d') }}"
                               required>
                        @error('date_of_birth')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="form-label">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" class="form-select" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Blood Group -->
                    <div>
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                        @error('blood_group')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nationality -->
                    <div>
                        <label class="form-label">Nationality</label>
                        <input type="text"
                               name="nationality"
                               value="{{ old('nationality', $student->nationality) }}"
                               class="form-input"
                               placeholder="Nigerian">
                        @error('nationality')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Religion -->
                    <div>
                        <label class="form-label">Religion</label>
                        <select name="religion" class="form-select">
                            <option value="">Select Religion</option>
                            @foreach(['Islam', 'Christianity', 'Other'] as $religion)
                                <option value="{{ $religion }}" {{ old('religion', $student->religion) === $religion ? 'selected' : '' }}>{{ $religion }}</option>
                            @endforeach
                        </select>
                        @error('religion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Place of Birth -->
                    <div>
                        <label class="form-label">Place of Birth</label>
                        <input type="text"
                               name="place_of_birth"
                               value="{{ old('place_of_birth', $student->place_of_birth) }}"
                               class="form-input"
                               placeholder="Lagos, Nigeria">
                        @error('place_of_birth')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-3">
                        <label class="form-label">Residential Address</label>
                        <textarea name="address"
                                  class="form-textarea"
                                  rows="2"
                                  placeholder="123 Main Street, City, State">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-graduation-cap mr-2 text-primary-600"></i>
                    Academic Information
                </h2>
            </div>
            <div class="card-body">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-id-card text-blue-600 text-xl mt-1"></i>
                        <div>
                            <p class="text-sm text-blue-900 font-medium">Admission Number: {{ $student->admission_number }}</p>
                            <p class="text-xs text-blue-700 mt-1">Admission Date: {{ $student->admission_date ? $student->admission_date->format('F d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Session Year -->
                    <div>
                        <label class="form-label">Session/Academic Year <span class="text-red-500">*</span></label>
                        <select name="session_year" class="form-select" required>
                            <option value="">Select Session Year</option>
                            @foreach(['2024/2025', '2025/2026', '2026/2027'] as $session)
                                <option value="{{ $session }}" {{ old('session_year', $student->session_year) === $session ? 'selected' : '' }}>{{ $session }}</option>
                            @endforeach
                        </select>
                        @error('session_year')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Level -->
                    <div>
                        <label class="form-label">Class Level <span class="text-red-500">*</span></label>
                        <select name="class_level" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach(['Nursery 1', 'Nursery 2', 'Primary 1', 'Primary 2', 'Primary 3', 'Primary 4', 'Primary 5', 'Primary 6', 'JSS 1', 'JSS 2', 'JSS 3', 'SSS 1', 'SSS 2', 'SSS 3'] as $class)
                                <option value="{{ $class }}" {{ old('class_level', $student->class_level) === $class ? 'selected' : '' }}>{{ $class }}</option>
                            @endforeach
                        </select>
                        @error('class_level')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section -->
                    <div>
                        <label class="form-label">Section</label>
                        <select name="section" class="form-select">
                            <option value="">Select Section</option>
                            @foreach(['A', 'B', 'C', 'D'] as $section)
                                <option value="{{ $section }}" {{ old('section', $student->section) === $section ? 'selected' : '' }}>Section {{ $section }}</option>
                            @endforeach
                        </select>
                        @error('section')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Roll Number -->
                    <div>
                        <label class="form-label">Roll Number</label>
                        <input type="text"
                               name="roll_number"
                               value="{{ old('roll_number', $student->roll_number) }}"
                               class="form-input"
                               placeholder="001">
                        @error('roll_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="form-label">Admission Status <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach([
                                'active' => ['label' => 'Active', 'color' => 'green', 'icon' => 'check-circle'],
                                'pending' => ['label' => 'Pending', 'color' => 'yellow', 'icon' => 'clock'],
                                'inactive' => ['label' => 'Inactive', 'color' => 'gray', 'icon' => 'pause-circle'],
                                'graduated' => ['label' => 'Graduated', 'color' => 'blue', 'icon' => 'graduation-cap'],
                                'withdrawn' => ['label' => 'Withdrawn', 'color' => 'red', 'icon' => 'times-circle'],
                                'suspended' => ['label' => 'Suspended', 'color' => 'orange', 'icon' => 'ban']
                            ] as $statusValue => $statusInfo)
                                <label class="relative flex items-center p-3 bg-white rounded-lg border-2 cursor-pointer hover:border-{{ $statusInfo['color'] }}-500 transition-colors"
                                       :class="'{{ old('status', $student->status) === $statusValue ? 'border-' . $statusInfo['color'] . '-500 bg-' . $statusInfo['color'] . '-50' : 'border-gray-200' }}'">
                                    <input type="radio" name="status" value="{{ $statusValue }}" class="hidden" {{ old('status', $student->status) === $statusValue ? 'checked' : '' }} required>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-{{ $statusInfo['icon'] }} text-{{ $statusInfo['color'] }}-600"></i>
                                        <span class="text-sm font-medium text-gray-900">{{ $statusInfo['label'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-address-book mr-2 text-primary-600"></i>
                    Student Contact Information
                </h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div>
                        <label class="form-label">Email Address</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $student->email) }}"
                               class="form-input"
                               placeholder="student@example.com">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input type="tel"
                               name="phone"
                               value="{{ old('phone', $student->phone) }}"
                               class="form-input"
                               placeholder="+234 XXX XXX XXXX">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Parent/Guardian Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-users mr-2 text-primary-600"></i>
                    Parent/Guardian Information
                </h2>
            </div>
            <div class="card-body space-y-8">
                <!-- Primary Parent/Guardian -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-sm mr-2">Primary Contact</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Full Name</label>
                            <input type="text"
                                   name="parent1_name"
                                   value="{{ old('parent1_name', $student->parent1_name) }}"
                                   class="form-input"
                                   placeholder="John Doe">
                            @error('parent1_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Relationship</label>
                            <select name="parent1_relationship" class="form-select">
                                <option value="">Select Relationship</option>
                                @foreach(['Father', 'Mother', 'Guardian', 'Other'] as $rel)
                                    <option value="{{ $rel }}" {{ old('parent1_relationship', $student->parent1_relationship) === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                @endforeach
                            </select>
                            @error('parent1_relationship')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Phone Number</label>
                            <input type="tel"
                                   name="parent1_phone"
                                   value="{{ old('parent1_phone', $student->parent1_phone) }}"
                                   class="form-input"
                                   placeholder="+234 XXX XXX XXXX">
                            @error('parent1_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Email Address</label>
                            <input type="email"
                                   name="parent1_email"
                                   value="{{ old('parent1_email', $student->parent1_email) }}"
                                   class="form-input"
                                   placeholder="parent@example.com">
                            @error('parent1_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Occupation</label>
                            <input type="text"
                                   name="parent1_occupation"
                                   value="{{ old('parent1_occupation', $student->parent1_occupation) }}"
                                   class="form-input"
                                   placeholder="Engineer">
                            @error('parent1_occupation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Secondary Parent/Guardian -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm mr-2">Secondary Contact (Optional)</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Full Name</label>
                            <input type="text"
                                   name="parent2_name"
                                   value="{{ old('parent2_name', $student->parent2_name) }}"
                                   class="form-input"
                                   placeholder="Jane Doe">
                            @error('parent2_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Relationship</label>
                            <select name="parent2_relationship" class="form-select">
                                <option value="">Select Relationship</option>
                                @foreach(['Father', 'Mother', 'Guardian', 'Other'] as $rel)
                                    <option value="{{ $rel }}" {{ old('parent2_relationship', $student->parent2_relationship) === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                @endforeach
                            </select>
                            @error('parent2_relationship')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Phone Number</label>
                            <input type="tel"
                                   name="parent2_phone"
                                   value="{{ old('parent2_phone', $student->parent2_phone) }}"
                                   class="form-input"
                                   placeholder="+234 XXX XXX XXXX">
                            @error('parent2_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Email Address</label>
                            <input type="email"
                                   name="parent2_email"
                                   value="{{ old('parent2_email', $student->parent2_email) }}"
                                   class="form-input"
                                   placeholder="parent2@example.com">
                            @error('parent2_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Occupation</label>
                            <input type="text"
                                   name="parent2_occupation"
                                   value="{{ old('parent2_occupation', $student->parent2_occupation) }}"
                                   class="form-input"
                                   placeholder="Teacher">
                            @error('parent2_occupation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Previous School Information -->
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-school mr-2 text-primary-600"></i>
                    Previous School Information
                </h2>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" x-model="showPreviousSchool" class="form-checkbox">
                    <span class="text-sm text-gray-600">Student attended previous school</span>
                </label>
            </div>
            <div class="card-body" x-show="showPreviousSchool" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="form-label">School Name</label>
                        <input type="text"
                               name="previous_school_name"
                               value="{{ old('previous_school_name', $student->previous_school_name) }}"
                               class="form-input"
                               placeholder="ABC International School">
                        @error('previous_school_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">School Address</label>
                        <textarea name="previous_school_address"
                                  class="form-textarea"
                                  rows="2"
                                  placeholder="School address...">{{ old('previous_school_address', $student->previous_school_address) }}</textarea>
                        @error('previous_school_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Last Grade/Class Attended</label>
                        <input type="text"
                               name="previous_school_grade"
                               value="{{ old('previous_school_grade', $student->previous_school_grade) }}"
                               class="form-input"
                               placeholder="Grade 5">
                        @error('previous_school_grade')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Year Left</label>
                        <input type="number"
                               name="previous_school_year"
                               value="{{ old('previous_school_year', $student->previous_school_year) }}"
                               class="form-input"
                               min="2000"
                               max="{{ date('Y') }}"
                               placeholder="2024">
                        @error('previous_school_year')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Reason for Leaving</label>
                        <textarea name="previous_school_reason"
                                  class="form-textarea"
                                  rows="2"
                                  placeholder="Relocation, better opportunities, etc.">{{ old('previous_school_reason', $student->previous_school_reason) }}</textarea>
                        @error('previous_school_reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Health & Medical Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-heartbeat mr-2 text-primary-600"></i>
                    Health & Medical Information
                </h2>
            </div>
            <div class="card-body space-y-6">
                <!-- Allergies -->
                <div>
                    <label class="form-label">Allergies</label>
                    @php
                        $existingAllergies = old('allergies') ?: ($student->allergies ? json_decode($student->allergies, true) : []);
                        $existingAllergies = is_array($existingAllergies) ? $existingAllergies : [];
                    @endphp
                    <div x-data="{ allergies: {{ json_encode($existingAllergies) }}, newAllergy: '' }">
                        <div class="flex items-center space-x-2 mb-3">
                            <input type="text"
                                   x-model="newAllergy"
                                   @keydown.enter.prevent="if(newAllergy.trim()) { allergies.push(newAllergy.trim()); newAllergy = ''; }"
                                   class="form-input flex-1"
                                   placeholder="Type allergy and press Enter (e.g., Peanuts, Dust)">
                            <button type="button"
                                    @click="if(newAllergy.trim()) { allergies.push(newAllergy.trim()); newAllergy = ''; }"
                                    class="btn btn-outline">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(allergy, index) in allergies" :key="index">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-red-100 text-red-800">
                                    <span x-text="allergy"></span>
                                    <button type="button"
                                            @click="allergies.splice(index, 1)"
                                            class="ml-2 text-red-600 hover:text-red-800">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                    <input type="hidden" name="allergies[]" :value="allergy">
                                </span>
                            </template>
                            <template x-if="allergies.length === 0">
                                <span class="text-sm text-gray-500 italic">No allergies added</span>
                            </template>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Add any known allergies the student has</p>
                    @error('allergies')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Medical Conditions -->
                <div>
                    <label class="form-label">Medical Conditions</label>
                    <textarea name="medical_conditions"
                              class="form-textarea"
                              rows="3"
                              placeholder="Any chronic illnesses, conditions, or disabilities...">{{ old('medical_conditions', $student->medical_conditions) }}</textarea>
                    @error('medical_conditions')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Medications -->
                <div>
                    <label class="form-label">Current Medications</label>
                    <textarea name="medications"
                              class="form-textarea"
                              rows="2"
                              placeholder="List any medications the student is currently taking...">{{ old('medications', $student->medications) }}</textarea>
                    @error('medications')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Emergency Medical Consent -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="checkbox"
                               name="emergency_medical_consent"
                               value="1"
                               {{ old('emergency_medical_consent', $student->emergency_medical_consent) ? 'checked' : '' }}
                               class="form-checkbox mt-1">
                        <div>
                            <p class="font-medium text-gray-900">Emergency Medical Consent</p>
                            <p class="text-sm text-gray-600 mt-1">I authorize the school to seek emergency medical treatment for my child if I cannot be reached</p>
                        </div>
                    </label>
                    @error('emergency_medical_consent')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Special Needs -->
                <div>
                    <label class="form-label">Special Needs/Accommodations</label>
                    <textarea name="special_needs"
                              class="form-textarea"
                              rows="3"
                              placeholder="Any special educational needs, accommodations, or support required...">{{ old('special_needs', $student->special_needs) }}</textarea>
                    @error('special_needs')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-sticky-note mr-2 text-primary-600"></i>
                    Additional Notes
                </h2>
            </div>
            <div class="card-body">
                <textarea name="notes"
                          class="form-textarea"
                          rows="4"
                          placeholder="Any additional information about the student...">{{ old('notes', $student->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span class="text-red-500">*</span> indicates required fields
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Update Student
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function studentForm() {
        return {
            showPreviousSchool: {{ $student->previous_school_name ? 'true' : 'false' }},

            init() {
                // Set max date for date of birth to today
                const dobInput = document.querySelector('input[name="date_of_birth"]');
                if (dobInput) {
                    dobInput.setAttribute('max', new Date().toISOString().split('T')[0]);
                }
            }
        }
    }
</script>
@endpush
@endsection
