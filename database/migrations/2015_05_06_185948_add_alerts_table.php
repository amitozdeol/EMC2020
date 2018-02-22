<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlertsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('alerts', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('building_id');
            $table->string('class_subscription');
            $table->integer('severity');
            $table->string('unsubscribe_key', 32);
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('alerts');
    }
}
