<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDashboardMapTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('dashboard_maps', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->integer('system_id');
      $table->string('label');
      $table->string('background_image');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('dashboard_maps');
  }

}
