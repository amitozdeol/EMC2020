<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('events', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->integer('system_id');
      $table->integer('device_id');
      $table->integer('zone');
      $table->integer("algorithm_id");
      $table->string('description',45);
      $table->integer('state');
      $table->integer('alarm_state');
      $table->integer('alarm_code_id');
      $table->datetime('cleared_at');
      $table->string('resolution',45)->default(null)->nullable();
      $table->boolean('active')->default(1);
      $table->time('duration');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('events');
  }

}
