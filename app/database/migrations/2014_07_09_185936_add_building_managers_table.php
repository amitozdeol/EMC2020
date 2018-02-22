<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildingManagersTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('building_managers', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->integer('user_id');
      $table->integer('building_id');
      $table->integer('role')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('building_managers');
  }

}
