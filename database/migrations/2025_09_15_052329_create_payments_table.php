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
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');

            //Payment details
            $table->string('method')->comment('credit_card, debit_card, online_banking, e_wallet');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->string('gateway')->nullable()->comment('stripe, paypal, fpx, etc.');
            $table->string('gateway_transaction_id')->nullable();
            $table->json('payment_metadata')->nullable()->comment('Store gateway-specific data');
            $table->string('session_id')->nullable()->comment('Link to payment session');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->decimal('refunded_amount', 10, 2)->default(0);
            $table->timestamp('refunded_at')->nullable();
            $table->text('refund_reason')->nullable();


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
