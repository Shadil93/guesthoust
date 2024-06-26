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
        Schema::create('used_extra_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('extra_service_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('booked_room_id')->nullable();
            $table->integer('qty')->default(0);
            $table->decimal('unit_price')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->date('service_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * 								
     */
    public function down(): void
    {
        Schema::dropIfExists('used_extra_services');
    }
};
