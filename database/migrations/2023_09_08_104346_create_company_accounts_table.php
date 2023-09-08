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
        Schema::create('company_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('iban', 50);
            $table->string('name', 50);
            $table->enum('type', ['cheking', 'saving', 'credit']);
            $table->string('bank_name', 50)->nullable();
            $table->foreignId('currency_id')->constrained('currencies');
            $table->foreignId('company_id')->constrained('companies');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_accounts');
    }
};
