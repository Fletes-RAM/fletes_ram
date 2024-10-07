<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnControlesVehiculares extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('controles_vehiculares', function ($table) {
				$table->string('origen')->after('control_vehicular');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('control_vehicular', function ($table) {
				$table->dropColumn('origen');
		});
	}

}
