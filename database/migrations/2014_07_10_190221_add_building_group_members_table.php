<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildingGroupMembersTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('building_group_members', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('building_group_id');
            $table->integer('building_id');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('building_group_members');
    }
}
