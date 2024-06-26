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
        Schema::create('booked_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->default(0);
            $table->unsignedBigInteger('room_type_id')->default(0);
            $table->unsignedBigInteger('room_id')->default(0);
            $table->date('booked_for')->nullable();
            $table->decimal('fare')->default(0);
            $table->decimal('tax_charge')->default(0);
            $table->decimal('cancellation_fee')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booked_rooms');
    }
};
