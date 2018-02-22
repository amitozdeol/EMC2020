<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriorityAlarmsAndEventsFields extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('mapping_output', function($table)
    {
        $table->boolean('priority_events')->default(0);
    });

    Schema::table('device_setpoints', function($table)
    {
        $table->boolean('priority_alarms')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('mapping_output', function($table)
    {
      $table->dropColumn('priority_events');
    });

    Schema::table('device_setpoints', function($table)
    {
      $table->dropColumn('priority_alarms');
    });
  }

}
