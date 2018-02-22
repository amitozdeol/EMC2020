<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandDeviceDataFloats extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::statement('ALTER TABLE device_data MODIFY COLUMN current_value FLOAT(8, 3)');
    DB::statement('ALTER TABLE device_data_current MODIFY COLUMN current_value FLOAT(8, 3)');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::statement('ALTER TABLE device_data MODIFY COLUMN current_value FLOAT(8, 2)');
    DB::statement('ALTER TABLE device_data_current MODIFY COLUMN current_value FLOAT(8, 2)');
  }

}
