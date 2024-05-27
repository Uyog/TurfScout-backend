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
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("turf_id");
            $table->foreign("turf_id")->references("id")->on("turfs");
            $table->string("duration");
            $table->double("total_price");
            $table->string("booking_status");
            $table->unsignedInteger('rating')->nullable();
            $table->text('review')->nullable();
            $table->dateTime("booking_time")->unique();
            $table->unsignedInteger("ball")->default(0);
            $table->unsignedInteger("bib")->default(0);
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
