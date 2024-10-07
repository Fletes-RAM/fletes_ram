<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOperadoresColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('operadores', function($table){
			$table->integer('unidad_id')->unsigned()->after('medica');
			$table->integer('cliente_id')->unsigned()->after('unidad_id');
			$table->string('origen')->after('cliente_id');
			$table->string('destino')->after('origen');
			$table->string('estatus')->after('destino');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('operadores', function($table){
			$table->dropColumn('unidad_id');
			$table->dropColumn('cliente_id');
			$table->dropColumn('origen');
			$table->dropColumn('destino');
			$table->dropColumn('estatus');
		});
	}

}
