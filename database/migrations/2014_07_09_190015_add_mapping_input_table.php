<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMappingInputTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('mapping_input', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('system_id');
            $table->integer('device_id');
            $table->integer('input_id');
            $table->integer('algorithm_id');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('mapping_input');
    }
}
