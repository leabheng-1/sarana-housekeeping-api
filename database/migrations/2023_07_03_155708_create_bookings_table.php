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
            $table->unsignedBigInteger('guest_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('payment_id');
            $table->boolean('is_cancel')->default(false);
            $table->date('cancel_date')->nullable();
            $table->date('arrival_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->date('checkin_date')->nullable();
            $table->date('checkout_date')->nullable();
            $table->integer('adults');
            $table->integer('child')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->text('note')->nullable();
            $table->timestamps();
    
            // Add foreign key constraints if necessary
            $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
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
