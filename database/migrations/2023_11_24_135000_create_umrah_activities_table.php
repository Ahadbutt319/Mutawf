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
        Schema::create('umrah_activities', function (Blueprint $table) {
            $table->id();
            $table->json('locations');
            $table->string('title');
            $table->string('vehicle');
            $table->decimal('price'); // Assuming a decimal type for the price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umrah_activities');
    }
};
