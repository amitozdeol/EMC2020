<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlgorithmsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('algorithms', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->string('algorithm_name', 45); // name 
      $table->string('function_type', 100);  // function from device types table ie temperature,humidity
      $table->text('description');
      $table->integer('inputs_req');  // vector of inputs
      $table->integer('inputs_max'); // reserve input for auto replacement
      $table->integer('polarity');  // state of enabled output
      $table->string('logicmode', 10); // logic to perform on inputs and,or
      $table->integer('ondelay');  // time in seconds before output response on
      $table->integer('offdelay');  // time in seconds before output response off
      $table->integer('duration');  // time in seconds to off after on irrespective of input  states
      $table->integer('votes'); // # inputs in vote
      $table->integer('season'); // seasonal response 0 - always active, 1 summer 2 winter
      $table->integer('response'); //enable in response to input states or calculated setpoints
                                   // use for variable timing in response to set backs etc
      $table->integer('basesetpoint');  // default setpoint for state determiniation for this algorithm
                                        // used when set backs are defined
    
   
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('algorithms');
  }

}