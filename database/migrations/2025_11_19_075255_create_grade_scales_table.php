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
        Schema::create('grade_scales', function (Blueprint $table) {
            $table->id();
            $table->string('grade', 5); // A, B, C, D, E, F
            $table->decimal('min_score', 5, 2); // Minimum score for this grade
            $table->decimal('max_score', 5, 2); // Maximum score for this grade
            $table->string('remark')->nullable(); // Excellent, Good, Fair, etc.
            $table->string('color', 7)->nullable(); // Hex color for UI
            $table->integer('order')->default(0); // Display order
            $table->boolean('is_passing')->default(true); // Is this a passing grade?
            $table->timestamps();

            // Ensure no overlapping ranges
            $table->unique(['min_score', 'max_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_scales');
    }
};
