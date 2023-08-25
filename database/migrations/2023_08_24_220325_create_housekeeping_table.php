<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousekeepingTable extends Migration
{
    public function up()
    {
        Schema::create('housekeeping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->string('housekeeper');
            $table->string('housekeeping_status');
            $table->date('date');
            $table->timestamps();

            // Define foreign key relationship
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('housekeeping');
    }
}
