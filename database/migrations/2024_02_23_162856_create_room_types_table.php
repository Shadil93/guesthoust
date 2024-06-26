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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('fare')->default(0);
            $table->decimal('cancellation_fee')->default(0);
            $table->integer('total_adult')->nullable();
            $table->integer('total_child')->nullable();
            $table->integer('feature_status')->nullable();
            $table->integer('status')->nullable();
            $table->text('description')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->text('beds')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
