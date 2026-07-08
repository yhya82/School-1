<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->decimal('amount_paid', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'online']);
            $table->string('transaction_ref', 100)->nullable()->unique();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payments_amount CHECK (amount_paid > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
