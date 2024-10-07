<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnVencimientoUnidadesOperadores extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('unidades', function($table){
			$table->date('vigencia')->after('aseguradora');
		});

		Schema::table('operadores', function($table){
			$table->string('licencia')->after('tel_contacto');
			$table->date('vigencia')->after('licencia');
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
				$table->dropColumn('vigencia');
		});

		Schema::table('operadores', function ($table) {
				$table->dropColumn('licencia');
				$table->dropColumn('vigencia');
		});
	}

}
