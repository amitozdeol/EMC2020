<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIndexes extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('alarms', function($table) {
      $table->index('system_id');
      $table->index('active');
    });

    Schema::table('device_data', function($table) {
      $table->index('system_id');
      $table->index('id');
    });

    Schema::table('devices', function($table) {
      $table->index('system_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('alarms', function($table) {
      $table->dropIndex('alarms_system_id_index');
      $table->dropIndex('alarms_active_index');
    });

    Schema::table('device_data', function($table) {
      $table->dropIndex('device_data_system_id_index');
      $table->dropIndex('device_data_id_index');
    });

    Schema::table('devices', function($table) {
      $table->dropIndex('devices_system_id_index');
    });
  }

}
