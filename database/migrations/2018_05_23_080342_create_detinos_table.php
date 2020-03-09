<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetinosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destinos', function (Blueprint $table) {
            $table->increments('id_destino');
            $table->string('name');
            $table->string('image');
            $table->string('description');
            $table->string('addres');
            $table->integer('ruta_id')->unsigned();
        });

        Schema::table('destinos', function($table) {
            $table->foreign('ruta_id')->references('id_ruta')->on('rutas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destinos');
    }
}
