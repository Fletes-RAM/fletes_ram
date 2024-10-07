<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsBitacorasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bitacoras', function (Blueprint $table) {
      $table->boolean('filtro_diesel_separador_agua')->after('filtro_aceite_hidraulico');
      $table->boolean('filtro_diesel_separador_condimentos')->after('filtro_diesel_separador_agua');
      $table->boolean('filtro_adblue')->after('filtro_diesel_separador_condimentos');

      $table->boolean('anticongelante')->after('aceite_liquido_frenos');
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
      $table->dropColumn('filtro_diesel_separador_agua');
      $table->dropColumn('filtro_diesel_separador_condimentos');
      $table->dropColumn('filtro_adblue');
      $table->dropColumn('anticongelante');
    });
	}

}
