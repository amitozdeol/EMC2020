<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceConfigUpdateTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('device_config_update', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('device_id');
            $table->integer('system_id');
            $table->integer('power_level');
            $table->integer('report_time');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('device_config_update');
    }
}
