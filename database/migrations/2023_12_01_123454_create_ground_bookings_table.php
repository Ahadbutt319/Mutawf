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
        Schema::create('ground_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('ground_id')->references('id')->on('ground_services')->onDelete('cascade');
            $table->date('pu_date');
            $table->string('name');
            $table->string('email');
            $table->integer('payment_id')->nullable();
            $table->string('persons');
            $table->longText('details');
            $table->longText('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ground_bookings');
    }
};
