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
        Schema::create('academic_terms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "First Term 2024/2025"
            $table->string('session'); // e.g., "2024/2025"
            $table->enum('term', ['First Term', 'Second Term', 'Third Term']);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed'])->default('upcoming');
            $table->timestamps();

            // Ensure unique term per session
            $table->unique(['session', 'term']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_terms');
    }
};
