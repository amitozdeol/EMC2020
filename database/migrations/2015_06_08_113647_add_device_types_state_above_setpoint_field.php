<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceTypesStateAboveSetpointField extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('device_types', function($table)
    {
        $table->boolean('state_above_setpoint')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('device_types', function($table)
    {
      $table->dropColumn('state_above_setpoint');
    });
  }

}
