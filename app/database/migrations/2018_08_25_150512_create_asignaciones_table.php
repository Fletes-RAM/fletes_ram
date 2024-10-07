<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignaciones', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('cotizacion_id')->unsigned();
            $table->foreign('cotizacion_id')->references('id')->on('cotizaciones')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('unidad_id')->unsigned();
            $table->foreign('unidad_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->string('terminado', 15);
            $table->timestamps();
        });

        Schema::create('asignaciones_combustibles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asignacion_id')->unsigned();
            $table->foreign('asignacion_id')->references('id')->on('asignaciones')->onDelete('cascade');
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
            $table->timestamps();
        });

        Schema::table('unidades', function ($table) {
            $table->double('km_inicial', 15, 2)->unsigned()->after('aseguradora');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unidades', function ($table) {
            $table->dropColumn('km_inicial');
        });
        Schema::drop('asignaciones_combustibles');
        Schema::drop('asignaciones');
    }
}
