<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceDataLongTerm extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('device_data_long_term', function($table)
    {
      $table->increments('recnum');
      $table->dateTime('datetime');
      $table->integer('id');
      $table->integer('system_id');
      $table->integer('command');
      $table->float('current_value', 8, 3);
      $table->boolean('current_state');
      $table->float('setpoint');
      $table->integer('alarm_state',0);
      $table->integer('alarm_index',0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('device_data_long_term');
  }

}
