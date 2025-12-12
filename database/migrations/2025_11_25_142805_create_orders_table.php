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
          Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('userID');
    $table->unsignedBigInteger('bookingID');
    $table->string('name');
    $table->string('orderStatus')->default('Not Started');
    $table->timestamps();

    $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('bookingID')->references('BookingID')->on('bookings')->onDelete('cascade');
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
