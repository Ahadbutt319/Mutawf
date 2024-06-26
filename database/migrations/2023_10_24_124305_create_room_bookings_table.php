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
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('added_by');
            $table->foreignId('room_category_id')->references('id')->on('room_categories');
            $table->string('price_per_night');
            $table->string('name');
            $table->string('room_number');
            $table->string('floor_number');
            $table->string('bed_type');
            $table->boolean('is_available')->default(true);
            $table->string('capacity');
            $table->foreignId('room_hotel_id')->references('id')->on('agent_hotels');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
    }
};
