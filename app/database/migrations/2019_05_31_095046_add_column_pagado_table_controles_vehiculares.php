<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPagadoTableControlesVehiculares extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('controles_vehiculares', function($table){
			//$table->string('pagado', 6)->after('iva');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('controles_vehiculares', function($table){
			//$table->dropColumn('pagado');
		});	}

}
