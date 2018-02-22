<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSystemsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('systems', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->integer('building_id');
      $table->integer('extender_boards');
      $table->integer('wired_relay_outputs');
      $table->integer('current_loop_outputs');
      $table->integer('wired_sensors');
      $table->integer('wireless_sensors');
      $table->integer('bacnet_devices');
      $table->integer('system_mode');
      $table->integer('season_mode');
      $table->integer('active_relays');
      $table->integer('current_loop_dvrs');
      $table->integer('current_loop_inputs');
      $table->integer('active_inputs1');
      $table->integer('active_inputs2');
      $table->integer('active_inputs3');
      $table->integer('active_inputs4');
      $table->string('ethernet_ip')->nullable();
      $table->string('wireless_ip')->nullable();
      $table->integer('ethernet_port');
      $table->integer('wireless_port');
      $table->integer('coordinator_mac');
      $table->integer('coordinator_format');
      $table->integer('alarm_state');
      $table->integer('alarm_index');
      $table->string('temperature_format')->default("C");
      $table->integer('system_zones');
      $table->boolean('update_flag')->default(0);
    });

    $statement = "ALTER TABLE `systems` AUTO_INCREMENT = 500;";
    DB::unprepared($statement);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('systems');
  }

}
