<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSystemHwVersion extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        DB::raw("ALTER TABLE systems ADD COLUMN hardware_version VARCHAR(20) NOT NULL DEFAULT '0' AFTER system_zones");
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        DB::raw('ALTER TABLE systems DROP COLUMN hardware_version');
    }
}
