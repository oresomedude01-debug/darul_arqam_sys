@extends('layouts.public')

@section('title', 'Student Information - Step 1 of 5')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-gray-700">Step 1 of 5</span>
            <span class="text-sm text-gray-600">20% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-primary-600 h-3 rounded-full transition-all duration-300" style="width: 20%"></div>
        </div>
    </div>

    <!-- Header -->
    <div class="card bg-gradient-to-r from-primary-50 to-blue-50 border-l-4 border-primary-500 mb-6">
        <div class="card-body">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-12 h-12 bg-primary-600 text-white rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Student Information</h2>
                    <p class="text-gray-700 text-sm">Please provide the student's personal details</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('enrollment.process-step1') }}" method="POST" enctype="multipart/form-data" x-data="{ preview: '{{ isset($data['photo_path']) ? asset('storage/' . $data['photo_path']) : '' }}' }">
        @csrf

        <div class="card mb-6">
            <div class="card-body space-y-6">
                <!-- Photo Upload -->
                <div>
                    <label class="form-label">Student Photo (Optional)</label>
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 rounded-full bg-gray-200 overflow-hidden">
                                <img x-show="preview" :src="preview" alt="Preview" class="w-full h-full object-cover">
                                <div x-show="!preview" class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-3xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="photo" accept="image/*" id="photo-upload" class="hidden"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                            <label for="photo-upload" class="btn btn-outline cursor-pointer">
                                <i class="fas fa-upload mr-2"></i>Choose Photo
                            </label>
                            <p class="text-xs text-gray-500 mt-2">PNG or JPG, maximum 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Name Fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" class="form-input" value="{{ old('first_name', $data['first_name'] ?? '') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-input" value="{{ old('middle_name', $data['middle_name'] ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" class="form-input" value="{{ old('last_name', $data['last_name'] ?? '') }}" required>
                    </div>
                </div>

                <!-- Date of Birth & Gender -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth" class="form-input" max="{{ date('Y-m-d') }}"
                               value="{{ old('date_of_birth', $data['date_of_birth'] ?? '') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" class="form-select" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $data['gender'] ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $data['gender'] ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $data['blood_group'] ?? '') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Nationality</label>
                        <input type="text" name="nationality" class="form-input" value="{{ old('nationality', $data['nationality'] ?? 'Nigerian') }}">
                    </div>
                    <div>
                        <label class="form-label">Religion</label>
                        <select name="religion" class="form-select">
                            <option value="">Select Religion</option>
                            @foreach(['Islam', 'Christianity', 'Other'] as $religion)
                                <option value="{{ $religion }}" {{ old('religion', $data['religion'] ?? '') === $religion ? 'selected' : '' }}>{{ $religion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Place of Birth</label>
                        <input type="text" name="place_of_birth" class="form-input" value="{{ old('place_of_birth', $data['place_of_birth'] ?? '') }}">
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $data['email'] ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-input" value="{{ old('phone', $data['phone'] ?? '') }}">
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="form-label">Residential Address</label>
                    <textarea name="address" class="form-textarea" rows="2">{{ old('address', $data['address'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('enrollment.token') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <button type="submit" class="btn btn-primary">
                Next Step <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </form>
</div>
@endsection
