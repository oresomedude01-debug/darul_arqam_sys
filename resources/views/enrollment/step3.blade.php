@extends('layouts.public')

@section('title', 'Health Information - Step 3 of 5')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Progress Stepper -->
    @include('enrollment._stepper', [
        'currentStep' => 3,
        'stepTitle' => 'Health & Medical Information',
        'stepDescription' => 'Medical details and allergies'
    ])

    <!-- Header -->
    <div class="card bg-gradient-to-r from-primary-50 to-blue-50 border-l-4 border-primary-500 mb-6">
        <div class="card-body">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-12 h-12 bg-primary-600 text-white rounded-full flex items-center justify-center">
                    <i class="fas fa-heartbeat text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Health & Medical Information</h2>
                    <p class="text-gray-700 text-sm">Help us ensure your child's safety and well-being</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('enrollment.process-step3') }}" method="POST">
        @csrf

        <div class="card mb-6">
            <div class="card-body space-y-6">
                <!-- Allergies -->
                <div>
                    <label class="form-label">Allergies</label>
                    <div x-data="{ allergies: {{ isset($data['allergies']) ? json_encode($data['allergies']) : '[]' }}, newAllergy: '' }">
                        <div class="flex items-center space-x-2 mb-3">
                            <input type="text" x-model="newAllergy"
                                   @keydown.enter.prevent="if(newAllergy.trim()) { allergies.push(newAllergy.trim()); newAllergy = ''; }"
                                   class="form-input flex-1" placeholder="Type allergy and press Enter (e.g., Peanuts, Dust)">
                            <button type="button" @click="if(newAllergy.trim()) { allergies.push(newAllergy.trim()); newAllergy = ''; }" class="btn btn-outline">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(allergy, index) in allergies" :key="index">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-red-100 text-red-800">
                                    <span x-text="allergy"></span>
                                    <button type="button" @click="allergies.splice(index, 1)" class="ml-2 text-red-600 hover:text-red-800">
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
                </div>

                <!-- Medical Conditions -->
                <div>
                    <label class="form-label">Medical Conditions</label>
                    <textarea name="medical_conditions" class="form-textarea" rows="3" placeholder="Any chronic illnesses, conditions, or disabilities...">{{ old('medical_conditions', $data['medical_conditions'] ?? '') }}</textarea>
                </div>

                <!-- Current Medications -->
                <div>
                    <label class="form-label">Current Medications</label>
                    <textarea name="medications" class="form-textarea" rows="2" placeholder="List any medications the student is currently taking...">{{ old('medications', $data['medications'] ?? '') }}</textarea>
                </div>

                <!-- Special Needs -->
                <div>
                    <label class="form-label">Special Needs/Accommodations</label>
                    <textarea name="special_needs" class="form-textarea" rows="3" placeholder="Any special educational needs, accommodations, or support required...">{{ old('special_needs', $data['special_needs'] ?? '') }}</textarea>
                </div>

                <!-- Emergency Medical Consent -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="checkbox" name="emergency_medical_consent" value="1"
                               {{ old('emergency_medical_consent', $data['emergency_medical_consent'] ?? false) ? 'checked' : '' }}
                               class="form-checkbox mt-1">
                        <div>
                            <p class="font-medium text-gray-900">Emergency Medical Consent</p>
                            <p class="text-sm text-gray-600 mt-1">I authorize the school to seek emergency medical treatment for my child if I cannot be reached</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('enrollment.step2') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <button type="submit" class="btn btn-primary">
                Next Step <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </form>
</div>
@endsection
