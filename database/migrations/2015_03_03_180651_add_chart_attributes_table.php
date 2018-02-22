<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChartAttributesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('chart_attributes', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('system_id');
            $table->integer('chart_id');
            $table->text('devices');
            $table->text('attributes');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('chart_attributes');
    }
}
