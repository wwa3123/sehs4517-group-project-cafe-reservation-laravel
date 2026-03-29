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
        Schema::create('tables', function (Blueprint $table) {
            $table->id('table_id');
            $table->string('name');
            $table->enum('type', ['Standard', 'Gaming', 'Private', 'VIP']);
            $table->integer('capacity');
            $table->string('photo_url')->nullable();
            $table->text('description')->nullable();
            $table->integer('min_players');
            $table->integer('min_time');
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
        Schema::dropIfExists('tables');
    }
};
