<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventariosMaterialesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventariosmateriales', function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('nombre', 100);
				$table->text('descripcion');
				$table->double('precio', 15, 2);
				$table->integer('existencia')->unsigned();
				$table->double('valor', 15, 2);
				$table->timestamps();
		});

		Schema::create('inventariosmaterialessalidas', function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('inventariomaterial_id')->unsigned();
				$table->integer('unidad_id')->unsigned();
				$table->integer('cantidad')->unsigned();
				$table->text('observaciones');
				$table->timestamps();
		});

		Schema::create('inventariosmaterialesentradas', function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('inventariomaterial_id')->unsigned();
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
		Schema::drop('inventariosmateriales');
		Schema::drop('inventariosmaterialessalidas');
		Schema::drop('inventariosmaterialesentradas');
	}

}
