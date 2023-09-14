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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('role_id')->constrained('roles');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->foreignId('nationality_country_id')->nullable()->constrained('countries');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('email_verification_code', 8)->nullable();
            $table->timestamp('email_verification_code_expires')->nullable();
            $table->string('phone_verification_code', 8)->nullable();
            $table->timestamp('phone_verification_code_expires')->nullable();
            $table->string('password');
            $table->timestamp('last_seen_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
