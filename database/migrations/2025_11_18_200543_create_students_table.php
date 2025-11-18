<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Admission Details
            $table->string('admission_number')->unique();
            $table->date('admission_date');
            $table->enum('status', ['pending', 'active', 'inactive', 'graduated', 'withdrawn', 'suspended'])->default('pending');

            // Personal Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('nationality')->nullable();
            $table->text('address')->nullable();
            $table->string('photo_path')->nullable();

            // Academic Information
            $table->string('class_level')->nullable(); // e.g., "JSS1"
            $table->string('section')->nullable(); // e.g., "A", "B"
            $table->string('session_year')->nullable(); // e.g., "2025/2026"
            $table->string('roll_number')->nullable();

            // Previous School Information
            $table->string('previous_school_name')->nullable();
            $table->text('previous_school_address')->nullable();
            $table->string('previous_class')->nullable();
            $table->text('transfer_reason')->nullable();
            $table->string('previous_result_path')->nullable(); // File path for uploaded result

            // Health & Allergy Information
            $table->json('allergies')->nullable(); // Array of allergies
            $table->text('medical_conditions')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->boolean('emergency_medical_consent')->default(false);

            // Contact Information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // Parent/Guardian 1
            $table->string('parent1_name')->nullable();
            $table->string('parent1_relationship')->nullable();
            $table->string('parent1_phone')->nullable();
            $table->string('parent1_email')->nullable();
            $table->string('parent1_occupation')->nullable();

            // Parent/Guardian 2
            $table->string('parent2_name')->nullable();
            $table->string('parent2_relationship')->nullable();
            $table->string('parent2_phone')->nullable();
            $table->string('parent2_email')->nullable();
            $table->string('parent2_occupation')->nullable();

            // Additional Info
            $table->string('preferred_contact_method')->nullable();
            $table->text('notes')->nullable();

            // Token Tracking
            $table->unsignedBigInteger('registration_token_id')->nullable();

            // Audit Fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes(); // For soft delete capability

            // Indexes
            $table->index('admission_number');
            $table->index('status');
            $table->index('class_level');
            $table->index('session_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
