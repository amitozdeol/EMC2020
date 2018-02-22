<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlarmCodesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('alarm_codes', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('description', 300);
            $table->integer('severity');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('alarm_codes');
    }
}
