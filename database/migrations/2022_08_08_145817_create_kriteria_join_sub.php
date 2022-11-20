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
        Schema::create('kriteria_join_sub', function (Blueprint $table) {
            $table->foreignId('kriteria_detail_id');
            $table->foreignId('sub_kriteria_id');
            $table->timestamps();

            $table->foreign('kriteria_detail_id')->on('kriteria_details')->references('id')->cascadeOnDelete();
            $table->foreign('sub_kriteria_id')->on('sub_kriteria')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kriteria_join_sub');
    }
};
