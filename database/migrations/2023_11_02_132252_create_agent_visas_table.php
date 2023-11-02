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
        Schema::create('agent_visas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('added_by')->constrained('users')->nullable();
            $table->string('visa');
            $table->string('duration');
            $table->string('visa_to');
            $table->string('immigration');
            $table->string('manage_by');
            $table->string('validity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_visas');
    }
};
