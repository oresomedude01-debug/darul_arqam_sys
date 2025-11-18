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
        Schema::create('registration_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token_code')->unique();
            $table->enum('status', ['active', 'disabled', 'consumed', 'expired'])->default('active');
            $table->string('session_year')->nullable(); // e.g., "2025/2026"
            $table->string('class_level')->nullable(); // e.g., "JSS1", "Nursery 2"
            $table->text('note')->nullable(); // Description/purpose of token
            $table->dateTime('expires_at')->nullable();
            $table->unsignedBigInteger('student_id')->nullable(); // Links to student if consumed
            $table->dateTime('consumed_at')->nullable();
            $table->string('consumed_by_ip')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); // Admin who created it
            $table->timestamps();

            // Indexes for better performance
            $table->index('token_code');
            $table->index('status');
            $table->index('session_year');

            // Foreign keys
            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_tokens');
    }
};
