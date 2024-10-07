<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiposDeUnidadesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tipos_de_unidades', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('tipo_de_unidad', 80);
			$table->integer('porcentaje');
			$table->text('observaciones');
			$table->softDeletes();
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
		Schema::drop('tipos_de_unidades');
	}

}
