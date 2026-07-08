<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('class_subject_id')->constrained('class_subject')->cascadeOnDelete();
            $table->date('exam_date')->nullable();
            $table->unsignedSmallInteger('max_marks');
            $table->unsignedSmallInteger('pass_marks');
            $table->timestamps();

            $table->unique(['exam_id', 'class_subject_id']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE exam_subjects ADD CONSTRAINT chk_exam_subjects_marks CHECK (pass_marks <= max_marks)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_subjects');
    }
};
