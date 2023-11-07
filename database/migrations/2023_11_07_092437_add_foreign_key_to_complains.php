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
        Schema::table('complains', function (Blueprint $table) {
            $table->foreignId('complaint_status_id')->references('id')->on('complaint_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complains', function (Blueprint $table) {
            $table->dropForeign(['complaint_status_id']);
            $table->dropColumn('complaint_status_id');
        });
    }
};
