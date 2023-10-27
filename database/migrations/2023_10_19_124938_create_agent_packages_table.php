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
        Schema::create('agent_packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_name');
            $table->string('duration');
            $table->string('visa')->nullable();
            $table->text('details');
            $table->text('additional_notes')->nullable();
            $table->string('travel')->nullable();
            $table->string('managed_by');
            $table->string('hotel')->nullable();
            $table->foreignId('added_by')->constrained('users')->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_packages');
    }
};
