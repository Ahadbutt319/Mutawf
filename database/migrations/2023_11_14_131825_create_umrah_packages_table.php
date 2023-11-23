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
        Schema::create('umrah_packages', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('name');
            $table->longText('details');
            $table->string('managed_by');
            $table->string('duration');
            $table->integer('person');
            $table->string('type');
            $table->string('first_start')->nullable();
            $table->string('first_end')->nullable();
            $table->string('second_start')->nullable();
            $table->string('second_end')->nullable();
            $table->json('tags')->nullable();
            $table->string('transport')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->integer('price');
            $table->boolean('package_status')->default(false);
            $table->timestamps();
        });
        Schema::create('hotel_package', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->references('id')->on('agent_hotels')->onDelete('cascade');
            $table->foreignId('package_id')->references('id')->on('umrah_packages')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('package_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('package_id')->references('id')->on('umrah_packages')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('package_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('package_id')->references('id')->on('umrah_packages')->onDelete('cascade');
            $table->boolean('payment_status')->default(true);
            $table->datetime('date')->nullable();
            $table->integer('payment_id');
            $table->string('to');
            $table->string('from');
            $table->string('quantity');
            $table->string('total_amount');
            $table->timestamps();
        });
        Schema::create('package_booking_persons_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->foreignId('booking_id')->references('id')->on('package_bookings')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('visas', function (Blueprint $table) {
            $table->id();
            $table->string('passport_number');
            $table->string('nationality')->nullable();
            $table->string('id_number')->nullable();
            $table->string('visa_number')->nullable();
            $table->string('passport_image')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('booking_id')->references('id')->on('package_bookings')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('bookpackages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->references('id')->on('umrah_packages')->onDelete('cascade');
            $table->foreignId('booking_id')->references('id')->on('package_bookings')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::table('hotel_package', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
            $table->dropForeign(['package_id']);
        });
        Schema::table('package_bookings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['package_id']);
        });
        Schema::table('package_activities', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['package_id']);
        });
        Schema::table('bookpackages', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['package_id']);
        });
        Schema::table('visas', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);

        });
        Schema::table('package_booking_persons_details', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);

        });
        Schema::dropIfExists('visas');
        Schema::dropIfExists('package_booking_persons_details');
        Schema::dropIfExists('package_bookings');
        Schema::dropIfExists('umrah_packages');
        Schema::dropIfExists('package_activities');
        Schema::dropIfExists('hotel_package');
        Schema::dropIfExists('bookpackages');
    }
};
