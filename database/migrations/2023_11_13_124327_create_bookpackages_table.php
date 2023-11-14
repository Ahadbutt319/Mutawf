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
        Schema::create('bookpackages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->references('id')->on('agent_packages')->onDelete('cascade');
            $table->foreignId('booking_id')->references('id')->on('package_bookings')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookpackages');
    }
};
