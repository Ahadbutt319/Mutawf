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
        Schema::create('package_keys', function (Blueprint $table) {
            $table->id();
            $table->boolean('hotel')->default(false);
            $table->boolean('visa')->default(false);
            $table->boolean('travel')->default(false);
            $table->foreignId('package')->constrained('agent_packages')->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_keys');
    }
};
