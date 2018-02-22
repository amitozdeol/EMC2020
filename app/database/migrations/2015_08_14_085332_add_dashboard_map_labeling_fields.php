<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDashboardMapLabelingFields extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('dashboard_maps', function($table)
    {
      $table->string('tab_names', 20)->nullable();
      $table->string('zone_numbers', 20)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('dashboard_maps', function($table)
    {
      $table->dropColumn('tab_names');
      $table->dropColumn('zone_numbers');
    });
  }

}
