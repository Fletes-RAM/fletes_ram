<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNombresRutasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nombres_rutas', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('nombre', 200);
			$table->string('total_km',10);
			$table->text('observaciones');
			$table->softDeletes();
			$table->timestamps();
		});

		Schema::create('detalles_rutas', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->integer('nombre_id')->unsigned();
			$table->foreign('nombre_id')->references('id')->on('nombres_rutas')->onDelete('cascade');
			$table->string('estado', 200);
			$table->string('origen', 200);
			$table->string('estado_destino', 200);
			$table->string('destino', 200);
			$table->string('km', 10);
			$table->softDeletes();
			$table->timestamps();
		});

		Schema::create('codigos', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->smallInteger('idEstado');
			$table->string('estado', 35);
			$table->smallInteger('idMunicipio');
			$table->string('municipio', 60);
			$table->string('ciudad', 60);
			$table->string('zona', 15);
			$table->mediumInteger('cp');
			$table->string('asentamiento', 70);
			$table->string('tipo', 200);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('detalles_rutas');
		Schema::drop('nombres_rutas');
		Schema::drop('codigos');
	}

}
