<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildingGroupManagersTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('building_group_managers', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('building_group_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('building_group_managers');
    }
}
