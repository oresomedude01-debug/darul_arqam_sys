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
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Primary 1", "JSS 2", "SSS 3"
            $table->string('section')->nullable(); // e.g., "A", "B", "Gold", "Diamond"
            $table->string('class_code')->unique(); // e.g., "PRI1-A", "JSS2-B"
            $table->unsignedBigInteger('class_teacher_id')->nullable(); // Main class teacher
            $table->text('subject_teachers')->nullable(); // JSON array of {subject: "Math", teacher_id: 1}
            $table->integer('capacity')->default(30); // Maximum number of students
            $table->integer('current_enrollment')->default(0); // Current number of students
            $table->string('room_number')->nullable(); // Classroom location
            $table->string('academic_year')->nullable(); // e.g., "2024/2025"
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->text('description')->nullable(); // Additional notes
            $table->time('start_time')->nullable(); // Class start time
            $table->time('end_time')->nullable(); // Class end time
            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('class_teacher_id')->references('id')->on('teachers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};
