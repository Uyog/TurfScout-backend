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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('turf_id');
            $table->integer('duration'); 
            $table->double('total_price');
            $table->string('booking_status');
            $table->integer('pitch_number');
            $table->dateTime('booking_time');
            $table->dateTime('booking_end_time');
            $table->unsignedInteger('ball')->default(0);
            $table->unsignedInteger('bib')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('turf_id')->references('id')->on('turfs')->onDelete('cascade');
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
