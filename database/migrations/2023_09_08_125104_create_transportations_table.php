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
        Schema::create('transportations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->string('name')->nullable();
            $table->json('description')->nullable();
            $table->enum('type',['flight', 'cab', 'bus']);
            $table->string('departure_location');
            $table->string('arrival_location');
            $table->string('model', 10)->nullable();
            $table->string('reg_number', 30)->nullable();
            $table->decimal('price')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportations');
    }
};
