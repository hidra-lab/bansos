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
        Schema::create('dtks_bansos', function (Blueprint $table) {
            $table->foreignId('dtks_id');
            $table->foreignId('bansos_id');
            $table->timestamps();

            $table->foreign('dtks_id')->on('dtks')->references('id');
            $table->foreign('bansos_id')->on('bansos')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dtks_bansos');
    }
};
