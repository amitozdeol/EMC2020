<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDevicesSetback extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('device_setback', function($table)
    {
      $table->increments('recnum');      
      $table->timestamps();      
      $table->integer('system_id');
      $table->integer('device_id');
      $table->integer('command');
      $table->integer('index');
      $table->integer('starttime');
      $table->integer('stoptime');
      $table->float('setback');
      
     
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('device_setback');
  }

}