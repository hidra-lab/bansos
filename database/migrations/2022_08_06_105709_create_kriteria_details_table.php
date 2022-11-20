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
        Schema::create('kriteria_details', function (Blueprint $table) {
            $table->id();
            $table->string('kriteria');
            $table->string('simbol');
            $table->foreignId('level_d_m_id');
            $table->foreignId('jenis_kriteria_id');
            $table->foreignId('sub_kriteria_id');
            $table->timestamps();

            $table->foreign('level_d_m_id')->on('level_d_m')->references('id');
            $table->foreign('jenis_kriteria_id')->on('jenis_kriteria')->references('id');
            $table->foreign('sub_kriteria_id')->on('sub_kriteria')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kriteria_detail');
    }
};
