<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstanceFieldToDevicesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('devices', function($table)
    {
        $table->integer('instance')->after('device_types_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('devices', function($table)
    {
      $table->dropColumn('instance');
    });
  }

}
