<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('unidades', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('unidad', 15);
			$table->integer('tipo_de_unidad_id')->unsigned();
			$table->foreign('tipo_de_unidad_id')->references('id')->on('tipos_de_unidades')->onDelete('cascade');
			$table->string('placas', 20);
			$table->string('serie', 30);
			$table->string('poliza', 30);
			$table->string('aseguradora', 50);
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
		Schema::drop('unidades');
	}

}
