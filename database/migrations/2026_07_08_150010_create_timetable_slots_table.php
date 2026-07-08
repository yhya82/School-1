<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetable_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('class_subject_id')->constrained('class_subject')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room', 30)->nullable();
            $table->timestamps();

            $table->unique(['section_id', 'day_of_week', 'start_time']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE timetable_slots ADD CONSTRAINT chk_timetable_slots_time CHECK (end_time > start_time)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_slots');
    }
};
