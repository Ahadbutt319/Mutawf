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
        Schema::create('agent_transportations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('added_by')->constrained('users')->nullable();
            $table->string('type');
            $table->string('availability');
            $table->string('location');
            $table->string('pickup');
            $table->integer('no_of_persons');
            $table->string('manage_by');
            $table->string('tags');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_transportations');
    }
};
