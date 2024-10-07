<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDieselAutorizadoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('diesel_autorizado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tipo_de_unidad_id');
            $table->string('origen');
            $table->string('destino');
            $table->string('lts_aut');
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
		Schema::drop('diesel_autorizado');
	}

}
