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
        Schema::create('umrahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_provider_id')->constrained('users');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('group_id')->constrained('groups');
            $table->foreignId('hotel_booking_id')->constrained('hotel_bookings');
            $table->foreignId('transport_booking_id')->constrained('transport_bookings');
            $table->enum('type', ['with visa', 'without visa']);
            $table->enum('status', ['pending', 'in progress', 'accepted', 'rejected', 'cancelled']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umrahs');
    }
};
