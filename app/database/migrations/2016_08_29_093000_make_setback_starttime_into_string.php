<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeSetbackStarttimeIntoString extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::statement('ALTER TABLE device_setback MODIFY COLUMN starttime VARCHAR(10)');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::statement('ALTER TABLE device_setback MODIFY COLUMN starttime INT(11)');
  }

}
