<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeEnvironmentalOffsetIntoFloatingPointVar extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::statement('ALTER TABLE device_setpoints MODIFY COLUMN environmental_offset FLOAT');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::statement('ALTER TABLE device_setpoints MODIFY COLUMN environmental_offset INT(11)');
  }

}
