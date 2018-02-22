<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceDataGain extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('device_types', function ($table) {
            $table->float('gain', 8, 4)->default(1);
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::table('device_types', function ($table) {
            $table->dropColumn('gain');
        });
    }
}
