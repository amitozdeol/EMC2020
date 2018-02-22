<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassToAlarmCodes extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('alarm_codes', function($table)
    {
        $table->string('alarm_class')->default('OAS');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('alarm_codes', function($table)
    {
      $table->dropColumn('alarm_class');
    });
  }

}
