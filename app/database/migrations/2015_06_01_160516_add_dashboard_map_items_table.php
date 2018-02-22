<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDashboardMapItemsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('dashboard_map_items', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->integer('map_id');
      $table->string('label');
      $table->string('icon');
      $table->float('x_position');
      $table->float('y_position');
      $table->integer('device_id');
      $table->integer('command');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('dashboard_map_items');
  }

}
