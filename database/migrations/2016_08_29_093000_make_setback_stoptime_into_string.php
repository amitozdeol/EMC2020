<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeSetbackStoptimeIntoString extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::statement('ALTER TABLE device_setback MODIFY COLUMN stoptime VARCHAR(10)');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::statement('ALTER TABLE device_setback MODIFY COLUMN stoptime INT(11)');
  }

}
