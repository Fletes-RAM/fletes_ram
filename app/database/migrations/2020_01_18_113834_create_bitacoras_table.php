<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBitacorasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bitacoras', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->integer('unidad_id')->unsigned();
			$table->date('fecha');
			$table->string('kilometraje');
			$table->string('titulo');
			$table->integer('proveedor_id')->unsigned();
			$table->double('costo',15,2);
			$table->text('observaciones');
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
		Schema::drop('bitacoras');
	}

}
