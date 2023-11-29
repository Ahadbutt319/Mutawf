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
        Schema::create('ground_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('guider_name');
            $table->string('pu_location');
            $table->integer('persons');
            $table->integer('price');
            $table->longText('description');
            $table->json('services');
            $table->date('start_date');
            $table->string('image');
            $table->timestamps();
        });
        Schema::create('ground_service_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ground_Service_id')->references('id')->on('ground_services')->onDelete('cascade');
            $table->string('visit_location');
            $table->longText('description');
            $table->string('image');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ground_service_activities', function (Blueprint $table) {
            $table->dropForeign(['ground_Service_id']);
        });
        Schema::dropIfExists('ground_service_activities');
        Schema::dropIfExists('ground_services');
    }
};
