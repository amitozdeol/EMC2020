<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDevicesSetpoints extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('device_setpoints', function($table)
    {
      $table->increments('recnum');      
      $table->timestamps();      
      $table->integer('system_id');
      $table->integer('device_id');
      $table->integer('command');
      $table->float('setpoint');
      $table->float('hysteresis');
      $table->float('alarm_high');
      $table->float('alarm_low');
      $table->integer('environmental_offset');
     
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('device_setpoints');
  }

}