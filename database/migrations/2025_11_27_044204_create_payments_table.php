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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->enum('payment_method', ['credit_card', 'debit_card', 'bank_transfer', 'ewallet', 'cash']);
            $table->string('payment_gateway', 100)->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->decimal('fee_amount', 10, 2)->default(0.00);
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('expiration_date')->nullable();
            $table->string('receipt_image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
