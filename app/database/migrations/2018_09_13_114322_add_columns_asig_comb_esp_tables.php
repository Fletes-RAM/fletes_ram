<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsAsigCombEspTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asignaciones_combustibles', function ($table) {
            $table->string('foto_tablero_km', 250)->after('foto_tablero_despues');
        });

        Schema::table('asignaciones_especiales', function ($table) {
            $table->string('foto_tablero_km', 250)->after('foto_tablero_despues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asignaciones_combustibles', function ($table) {
            $table->dropColumn('foto_tablero_km');
        });

        Schema::table('asignaciones_especiales', function ($table) {
            $table->dropColumn('foto_tablero_km');
        });
    }
}
