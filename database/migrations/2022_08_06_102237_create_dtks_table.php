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
        Schema::create('dtks', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 16);
            $table->string('nik', 16)->index();
            $table->date('tgl_lahir');
            $table->enum('jk',['laki-laki','perempuan']);
            $table->string('pekerjaan');
            $table->string('hub_keluarga');
            $table->foreignId('bansos_id');
            $table->timestamps();

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
        Schema::dropIfExists('dtks');
    }
};
