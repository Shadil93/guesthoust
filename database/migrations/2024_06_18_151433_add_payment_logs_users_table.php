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
        Schema::table('payment_logs', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('payment_mode_id')->nullable();


            $table->foreign('payment_mode_id')->references('id')->on('payment_modes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            //
            $table->dropColumn('payment_mode_id');
        });
    }
};
