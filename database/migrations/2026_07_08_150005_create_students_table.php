<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('admission_no', 30)->unique();
            $table->foreignId('class_id')->constrained('classes')->restrictOnDelete();
            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete();
            $table->date('dob');
            $table->enum('gender', ['M', 'F', 'O']);
            $table->date('admission_date');
            $table->enum('status', ['active', 'graduated', 'withdrawn'])->default('active');
            $table->timestamps();

            $table->index(['class_id', 'section_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
