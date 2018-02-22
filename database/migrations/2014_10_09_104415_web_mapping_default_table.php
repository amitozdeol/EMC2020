<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WebMappingDefaultTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('web_mapping_default', function($table)
    {
      $table->increments('recnum');
      $table->datetime('datetime');
      $table->integer('system_id');
      $table->integer('group_number');
      $table->text('group_name',25);
      $table->integer('subgroup_number');
      $table->text('subgroup_name',25);
      $table->integer('itemnumber');    
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
    Schema::dropIfExists('web_mapping_default');
  }

}
