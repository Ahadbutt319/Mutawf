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
            $table->string('Package_Name');
            $table->string('Duration');
            $table->string('Visa');
            $table->text('Details');
            $table->text('Additional_Notes')->nullable();
            $table->string('Travel');
            $table->string('Managed_by');
            $table->string('Images');
            $table->unsignedBigInteger('Added_by');
            $table->foreign('Added_by')->references('id')->on('users');
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
