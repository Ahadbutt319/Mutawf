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
        Schema::create('hotel_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->references('id')->on('agent_hotels')->onDelete('cascade');
            $table->string('image');
            $table->string('image_type');
            $table->timestamps();
        });
        Schema::create('rooms_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->references('id')->on('room_bookings')->onDelete('cascade');
            $table->string('image');
            $table->string('room_image_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms_images', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
        });
        Schema::table('hotel_images', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
        });
        Schema::dropIfExists('rooms_images');
        Schema::dropIfExists('hotel_images');
    }
};
