<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->restrictOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete();
            $table->string('name', 100);
            $table->decimal('amount', 10, 2);
            $table->enum('frequency', ['monthly', 'term', 'annual']);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE fee_structures ADD CONSTRAINT chk_fee_structures_amount CHECK (amount > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
