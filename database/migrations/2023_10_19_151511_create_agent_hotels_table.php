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
        Schema::create('agent_hotels', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name');
            $table->string('private_transport');
            $table->datetime('checkin_time')->nullable();
            $table->datetime('checkout_time')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->string('location');
            $table->string('luxuries');
            $table->text('details');
            $table->foreignId('added_by')->constrained('users')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_hotels');
    }
};
