<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->string('leave_type', 30);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE leaves ADD CONSTRAINT chk_leaves_dates CHECK (end_date >= start_date)');
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
