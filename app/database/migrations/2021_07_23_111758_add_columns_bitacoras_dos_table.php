<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsBitacorasDosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bitacoras', function (Blueprint $table) {
            $table->boolean('aceite_caja')->after('aceite_caja_diferencial');
            $table->boolean('filtro_secador_aire')->after('filtro_adblue');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bitacoras', function (Blueprint $table) {
            $table->dropColumn('aceite_caja');
        });
	}

}
