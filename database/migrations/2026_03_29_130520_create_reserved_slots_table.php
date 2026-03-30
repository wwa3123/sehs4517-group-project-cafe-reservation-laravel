<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserved_slots', function (Blueprint $table) {
            $table->id('reserved_slots_id');
            $table->unsignedBigInteger('time_slots_id');
            $table->unsignedBigInteger('reservation_id');
            $table->enum('source_type', ['RESERVATION', 'EVENT']);
            $table->unsignedBigInteger('table_id');
            $table->timestamps();

            $table->foreign('time_slots_id')->references('time_slots_id')->on('time_slots');
            $table->foreign('reservation_id')->references('reservation_id')->on('reservations');
            $table->foreign('table_id')->references('table_id')->on('tables');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserved_slots');
    }
};
