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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->nullable();
            $table->integer('user_id')->default(0);
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->text('guest_details')->nullable();
            $table->string('id_card')->nullable();
            $table->decimal('tax_charge')->default(0);
            $table->decimal('booking_fare')->default(0);
            $table->decimal('service_cost')->default(0);
            $table->decimal('extra_charge')->default(0);
            $table->decimal('extra_charge_subtracted')->default(0);
            $table->decimal('paid_amount')->default(0);
            $table->string('payment_method')->default(0);
            $table->decimal('cancellation_fee')->default(0);
            $table->decimal('refunded_amount')->default(0);
            $table->integer('key_status')->default(0);
            $table->integer('status')->default(0);
            $table->datetime('checked_in_at')->nullable();
            $table->datetime('checked_out_at')->nullable();
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
