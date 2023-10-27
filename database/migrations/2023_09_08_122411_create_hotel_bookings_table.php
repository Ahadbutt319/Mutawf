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
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('service_provider_id')->constrained('users');
            $table->foreignId('room_id')->constrained('rooms');
            $table->string('brn')->unique();
            $table->timestamp('checkin_date_time')->nullable();
            $table->timestamp('checkout_date_time')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'cancelled']);
            $table->enum('checkin_status', ['pending', 'checked in', 'no show']);
             $table->enum('checkout_status', ['pending', 'checked in', 'no show']);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};
