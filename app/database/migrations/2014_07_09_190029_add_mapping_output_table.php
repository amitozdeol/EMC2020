<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMappingOutputTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('mapping_output', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->integer('system_id');
      $table->integer('device_id');
      $table->integer('algorithm_id');
      $table->string('algorithm_name', 45); // name 
      $table->string('function_type', 25);  // function from device types table ie temperature,humidity
      $table->text('description');
      $table->integer('polarity');  // state of enabled output
      $table->string('logicmode', 10); // logic to perform on inputs and,or
      $table->integer('ondelay');  // time in seconds before output response on
      $table->integer('offdelay');  // time in seconds before output response off
      $table->integer('duration');  // time in seconds to off after on irrespective of input  states
      $table->integer('min_required_inputs'); // # inputs in vote
      $table->string('inputs',100);  // vector of inputs
      $table->string('reserveinputs',100); // reserve input for auto replacement
      $table->integer('season'); // seasonal response 0 - always active, 1 summer 2 winter
      $table->integer('response'); //enable in response to input states or calculated setpoints
                                   // use for variable timing in response to set backs etc
   
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('mapping_output');
  }

}
