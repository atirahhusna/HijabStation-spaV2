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
            $table->id('BookingID'); // Primary Key
            $table->unsignedBigInteger('userID'); // FK to users table
            $table->unsignedBigInteger('treatmentID'); // FK to treatments table
            $table->string('email'); // From logged-in user
            $table->string('name');  // From logged-in user
            $table->string('phone'); // From form input
            $table->string('staffName')->nullable(); // Optional staff selection

            // New slot-related fields
            $table->string('slotTime'); // e.g., "10:00 AM"
            $table->integer('slotNum'); // e.g., 3 (remaining slots or selected slot number)

            // Add date field
            $table->date('date'); // Date of the booking (controller will ensure it's not Monday)

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('treatmentID')->references('treatmentID')->on('treatments')->onDelete('cascade');
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
