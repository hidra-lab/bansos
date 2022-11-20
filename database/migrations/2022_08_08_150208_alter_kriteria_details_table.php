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
        Schema::table('kriteria_details', function (Blueprint $table) {
            $table->dropForeign(['jenis_kriteria_id']);
            $table->dropForeign(['sub_kriteria_id']);

            $table->dropColumn('jenis_kriteria_id');
            $table->dropColumn('sub_kriteria_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kriteria_details', function (Blueprint $table) {
            $table->foreignId('jenis_kriteria_id');
            $table->foreignId('sub_kriteria_id');

            $table->foreign('level_d_m_id')->on('level_d_m')->references('id');
            $table->foreign('jenis_kriteria_id')->on('jenis_kriteria')->references('id');
        });
    }
};
