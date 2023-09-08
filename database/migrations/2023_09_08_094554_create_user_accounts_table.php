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
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('card_number', 30);
            $table->string('card_name', 50);
            $table->enum('card_type', ['visa', 'master card', 'american express', 'discover', 'jcb', 'dinners club', 'maestro', 'unionpay', 'chaina unionpay', 'elo']);
            $table->string('security_code', 5);
            $table->foreignId('country_id')->constrained('countries');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_accounts');
    }
};
