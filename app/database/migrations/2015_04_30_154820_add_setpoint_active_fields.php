<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSetpointActiveFields extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('device_types', function($table)
    {
      $table->boolean('setpoint_active')->default(0);
      $table->boolean('algorithm_active')->default(0);
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
      $table->dropColumn('setpoint_active');
      $table->dropColumn('algorithm_active');
    });
  }

}
