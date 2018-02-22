<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBacnetObejctTypeFieldToDevicesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('devices', function ($table) {
            $table->integer('bacnet_object_type')->after('device_types_id')->nullable();
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::table('devices', function ($table) {
            $table->dropColumn('bacnet_object_type');
        });
    }
}
