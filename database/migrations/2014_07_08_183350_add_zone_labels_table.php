<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZoneLabelsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('zone_labels', function ($table) {
            $table->increments('recnum');
            $table->integer('system_id');
            $table->integer('zone');
            $table->string('zonename');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('zone_labels');
    }
}
