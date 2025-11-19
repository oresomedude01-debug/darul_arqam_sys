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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->onDelete('set null');
            $table->foreignId('exam_type_id')->constrained('exam_types')->onDelete('cascade');
            $table->string('term'); // First Term, Second Term, Third Term
            $table->string('session'); // 2024/2025, 2025/2026
            $table->decimal('score', 5, 2); // The actual score
            $table->string('grade', 5)->nullable(); // Computed grade (A, B, C...)
            $table->text('remark')->nullable(); // Teacher's remark
            $table->foreignId('recorded_by')->nullable()->constrained('teachers')->onDelete('set null');
            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['student_id', 'subject_id', 'exam_type_id', 'term', 'session']);

            // Indexes for common queries
            $table->index(['term', 'session']);
            $table->index(['school_class_id', 'term', 'session']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
