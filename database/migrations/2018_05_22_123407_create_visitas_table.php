<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->increments('id_visita');
            $table->integer('user_id')->unsigned();
            $table->integer('actividad_id')->unsigned();
            $table->smallInteger('visto');
            $table->timestamps();
        });

        Schema::table('visitas', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('actividad_id')->references('id_actividad')->on('actividads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitas');
    }
}
