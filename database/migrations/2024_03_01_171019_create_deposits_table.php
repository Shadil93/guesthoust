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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('method_code')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('method_currency')->nullable();
            $table->decimal('charge')->nullable();
            $table->decimal('rate')->nullable();
            $table->decimal('final_amo')->nullable();
            $table->text('detail')->nullable();
            $table->string('btc_amo')->nullable();
            $table->string('btc_wallet')->nullable();
            $table->string('trx')->nullable();
            $table->integer('payment_try')->nullable();
            $table->integer('status')->nullable();
            $table->integer('from_api')->nullable();
            $table->string('admin_feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
