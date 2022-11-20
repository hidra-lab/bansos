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
        Schema::table('dtks', function (Blueprint $table) {
            $table->dropForeign(['bansos_id']);

            $table->dropColumn('bansos_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dtks', function (Blueprint $table) {
            $table->foreignId('bansos_id');
            $table->foreign('bansos_id')->on('bansos')->references('id');
        });
    }
};
