<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsAsignaciones extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asignaciones_combustibles', function ($table) {
            $table->string('foto_tablero_antes', 250)->after('foto_ticket');
            $table->string('foto_tablero_despues', 250)->after('foto_tablero_antes');
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
            $table->dropColumn('foto_tablero_antes');
            $table->dropColumn('foto_tablero_despues');
        });
    }
}
