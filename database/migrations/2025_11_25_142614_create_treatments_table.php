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
    Schema::create('treatments', function (Blueprint $table) {
        $table->id('treatmentID'); // Primary Key
        $table->string('t_name');
        $table->decimal('t_price', 8, 2);
        $table->text('t_desc')->nullable();
        $table->string('t_pic')->nullable();
        $table->string('t_duration'); // In minutes, or whatever unit you prefer
        $table->integer('slotNum');     // number of slot
        $table->json('slotTime')->nullable(); // to store time slots and counts
 
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
