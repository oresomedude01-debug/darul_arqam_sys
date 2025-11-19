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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['term_start', 'term_end', 'holiday', 'exam', 'meeting', 'special'])->default('special');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('description')->nullable();
            $table->string('color', 7)->nullable(); // Hex color for calendar display
            $table->json('affected_classes')->nullable(); // Array of class IDs or 'all'
            $table->foreignId('academic_term_id')->nullable()->constrained('academic_terms')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('teachers')->onDelete('set null');
            $table->timestamps();

            // Indexes
            $table->index(['start_date', 'end_date']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
