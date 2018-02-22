<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDevicesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('devices', function($table)
    {
      $table->increments('recnum');
      $table->integer('id');
      $table->timestamps();
      $table->integer('building_id');
      $table->integer('system_id');
      $table->integer('device_types_id');
      $table->string('product_id', 10)->nullable();
      $table->string('device_mode', 15)->nullable();
      $table->string('device_io', 10)->nullable();
      $table->string('device_function', 20)->nullable();
      $table->string('functional_description', 100)->nullable();
      $table->string('mac_address')->default('00:00:00:00:00:00:00:00');
      $table->integer('short_address');
      $table->integer('location');
      $table->integer('zone');
      $table->string('name', 25)->nullable();
      $table->string('reporttime',10)->default("5");   // default report time in minutes  default for wireless is 5 minutes  COV for report on Change of Value
      $table->integer('powerlevel')->default(0);  // set to 2's complement of power level default to 0
      $table->string('physical_location', 50)->nullable();
      $table->string('comments')->nullable();
      $table->integer('status')->default(0);
      $table->integer('inhibited')->default(0);
      $table->integer('retired')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('devices');
  }

}
