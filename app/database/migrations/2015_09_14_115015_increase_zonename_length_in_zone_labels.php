<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseZonenameLengthInZoneLabels extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::statement('ALTER TABLE zone_labels MODIFY COLUMN zonename VARCHAR(255)');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::statement('ALTER TABLE zone_labels MODIFY COLUMN zonename VARCHAR(20)');
  }

}
