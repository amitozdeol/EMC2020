<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSensorSetpointsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('sensor_setpoints', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('sensor_id');
            $table->integer('min_value');
            $table->integer('max_value');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('sensor_setpoints');
    }
}
