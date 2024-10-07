<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventariosLlantasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('llantas', function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('clave', 100);
				$table->string('marca', 100);
				$table->string('medida', 100);
				$table->string('tipo', 100);
				$table->integer('existencia')->unsigned();
				$table->timestamps();
		});

		Schema::create('llantassalidas', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('llanta_id')->unsigned();
			$table->integer('unidad_id')->unsigned();
			$table->integer('cantidad')->unsigned();
			$table->text('observaciones');
			$table->timestamps();
	});

	Schema::create('llantasentradas', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('llanta_id')->unsigned();
			$table->integer('cantidad')->unsigned();
			$table->double('precio', 15, 2);
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
		Schema::drop('llantassalidas');
		Schema::drop('llantasentradas');
		Schema::drop('llantas');
	}

}
