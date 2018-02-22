<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlarmsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('alarms', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->integer('system_id');
      $table->integer('device_id');
      $table->integer('command');
      $table->integer('alarm_state');
      $table->integer('alarm_code_id');
      $table->string('description', 300);
      $table->datetime('cleared_at');
      $table->string('resolution', 45)->default(null)->nullable();
      $table->boolean('active')->default(1);
      $table->time('duration')->default(null)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('alarms');
  }

}
