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
        Schema::create('ground_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->constrained('users')->nullable();
            $table->string('hotels');
            $table->string('guider_name');
            $table->string('tour_location');
            $table->string('services');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ground_services');
    }
};
