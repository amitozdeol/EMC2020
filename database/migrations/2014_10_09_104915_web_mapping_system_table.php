<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WebMappingSystemTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('web_mapping_system', function($table)
    {
      $table->increments('recnum');
      $table->datetime('datetime');
      $table->integer('system_id');
      $table->integer('group_number');
      $table->text('group_name',25);
      $table->integer('subgroup_number');
      $table->text('subgroup_name',25);
      $table->integer('itemnumber');
      $table->integer('object_id');   // recum if teh object to be displayed
      $table->integer('alarm_state');
      $table->integer('alarm_index');
      $table->boolean('active');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('web_mapping_system');
  }

}
