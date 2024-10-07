<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsControlesVehicularesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('controles_vehiculares', function ($table) {
            $table->string('tarifa')->after('toneladas');
            $table->string('iva')->after('cantidad');
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
            $table->dropColumn('iva');
            $table->dropColumn('tarifa');
        });
    }
}
