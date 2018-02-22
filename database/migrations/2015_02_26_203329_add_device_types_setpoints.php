<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceTypesSetpoints extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('device_types', function ($table) {
            $table->decimal('setpoint', 8, 3)->after('hysteresis');
            $table->decimal('alarm_high', 8, 3)->after('setpoint');
            $table->decimal('alarm_low', 8, 3)->after('alarm_high');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::table('device_types', function ($table) {
            $table->dropColumn('setpoint');
            $table->dropColumn('alarm_high');
            $table->dropColumn('alarm_low');
        });
    }
}
