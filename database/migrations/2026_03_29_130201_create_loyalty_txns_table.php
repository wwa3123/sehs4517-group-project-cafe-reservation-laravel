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
        Schema::create('loyalty_txns', function (Blueprint $table) {
            $table->id('txn_id');
            $table->unsignedBigInteger('reference_id');
            $table->enum('reference_type', ['Reservation', 'Event']);
            $table->enum('txn_type', ['RESERVATION', 'EVENT']);
            $table->integer('points');
            $table->string('descriptions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loyalty_txns');
    }
};
