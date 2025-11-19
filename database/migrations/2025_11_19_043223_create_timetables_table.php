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
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time'); // e.g., "08:00"
            $table->time('end_time'); // e.g., "09:00"
            $table->integer('period_number')->default(1); // 1st period, 2nd period, etc.
            $table->enum('type', ['class', 'break', 'lunch', 'assembly'])->default('class');
            $table->string('room_number')->nullable(); // Override class default room if needed
            $table->text('notes')->nullable();
            $table->timestamps();

            // Prevent overlapping periods for the same class on the same day
            $table->unique(['school_class_id', 'day_of_week', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
