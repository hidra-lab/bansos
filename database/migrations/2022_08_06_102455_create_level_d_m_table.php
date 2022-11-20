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
        Schema::create('level_d_m', function (Blueprint $table) {
            $table->id();
            $table->string('jabatan');
            $table->foreignId('user_id');
            $table->timestamps();

            $table->foreign('user_id')->on('user')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_d_m');
    }
};
