<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildingGroupsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('building_groups', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->integer('customer_id');
      $table->string('name', 45);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('building_groups');
  }

}
