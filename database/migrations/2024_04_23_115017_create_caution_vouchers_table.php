<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up(): void
{
    Schema::create('caution_vouchers', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('booking_id');
        $table->decimal('caution_amt', 10, 2); 
        $table->unsignedBigInteger('voucher_id');
        $table->timestamps();

        $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caution_vouchers');
    }
};
