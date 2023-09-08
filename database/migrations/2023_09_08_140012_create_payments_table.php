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
            $table->foreignId('user_id')->constrained('users');
            $table->enum('method', ['card', 'bank', 'paypal']);
            $table->decimal('amount');
            $table->decimal('transaction_fee');
            $table->enum('status', ['pending', 'paid']);
            $table->string('gateway', 50);
            $table->text('description')->nullable();
            $table->string('reference')->unique();
            $table->enum('type', ['purchase', 'refund']);
            $table->foreignId('paymentable_id');
            $table->string('paymentable_type');
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
