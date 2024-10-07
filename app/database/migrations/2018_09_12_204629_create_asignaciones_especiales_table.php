<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionesEspecialesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignaciones_especiales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unidad_id')->unsigned();
            $table->foreign('unidad_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('fecha');
            $table->integer('gasolinera_id')->unsigned();
            $table->foreign('gasolinera_id')->references('id')->on('gasolineras')->onDelete('cascade');
            $table->string('ticket', 150);
            $table->double('litros', 15, 2)->unsigned();
            $table->double('precio', 15, 2)->unsigned();
            $table->double('total', 15, 2)->unsigned();
            $table->double('kilometraje', 15, 2)->unsigned();
            $table->double('rendimiento', 15, 2)->unsigned();
            $table->string('foto_ticket', 250);
            $table->string('foto_tablero_antes', 250);
            $table->string('foto_tablero_despues', 250);
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
        Schema::drop('asignaciones_especiales');
    }
}
