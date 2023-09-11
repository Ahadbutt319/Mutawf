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
        Schema::create('transport_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('seat_id')->nullable();
            $table->foreignId('transportation_id')->nullable();
            $table->string('brn')->unique();
            $table->json('description')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'in progress', 'cancelled']);
            $table->timestamp('departure_at')->nullable();
            $table->timestamp('arrive_at')->nullable();
            $table->enum('departure_status', ['scheduled', 'delayed', 'departed']);
            $table->enum('arrival_status', ['scheduled', 'delayed', 'arrived']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_bookings');
    }
};
