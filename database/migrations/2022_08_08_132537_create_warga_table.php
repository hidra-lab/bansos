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
        Schema::create('warga', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 16);
            $table->string('nik', 16)->index();
            $table->date('nama');
            $table->enum('jk', ['laki-laki', 'perempuan']);
            $table->date('tmp_lahir');
            $table->date('tgl_lahir');
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->string('status_perkawinan');
            $table->string('hub_keluarga');
            $table->date('ayah_warga');
            $table->date('ibu_warga');
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
        Schema::dropIfExists('warga');
    }
};
