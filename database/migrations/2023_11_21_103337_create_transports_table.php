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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('type', ['medium car', 'small car', 'large car', 'suvs', 'people carrier', 'premium car']);
            $table->string('location');
            $table->string('capacity');
            $table->decimal('price')->nullable();
            $table->boolean('is_active')->default(true);
            $table->longText('details');
            $table->timestamps();
        });
        Schema::create('transport_cars', function (Blueprint $table) {  
            $table->id();
            $table->string('image');
            $table->enum('type', ['automatic', 'manual']);
            $table->string('bags');
            $table->string('name');
            $table->foreignId('transport_id')->references('id')->on('transports')->onDelete('cascade'); 
            $table->timestamps();
        });
        Schema::create('transportation_bookings', function (Blueprint $table) {  
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('passengers');
            $table->string('luggages');
            $table->text('details');
            $table->string('type');
            $table->string('pickup');
            $table->string('drop_off')->nullable();
            $table->date('date');
            $table->time('time');
            $table->date('return_date')->nullable();
            $table->time('return_time')->nullable();
            $table->string('duration')->nullable();
            $table->foreignId('transport_id')->references('id')->on('transports')->onDelete('cascade'); 
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::table('transport_cars', function (Blueprint $table) {
            $table->dropForeign(['transport_id']);
        });
        Schema::table('transportation_bookings', function (Blueprint $table) {
            $table->dropForeign(['transport_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('transports');
        Schema::dropIfExists('transport_cars');
        Schema::dropIfExists('transport_bookings');
      
    }
};
