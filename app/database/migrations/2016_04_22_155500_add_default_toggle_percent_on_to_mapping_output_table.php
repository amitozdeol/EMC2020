<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultTogglePercentOnToMappingOutputTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::raw("ALTER TABLE mapping_output ADD COLUMN default_toggle_percent_on INT UNSIGNED NOT NULL DEFAULT '0' AFTER default_toggle_duration");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::raw('ALTER TABLE mapping_output DROP COLUMN default_toggle_percent_on');
  }

}
