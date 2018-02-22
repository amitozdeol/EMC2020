<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSystemsNetMacField extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        DB::raw("ALTER TABLE systems ADD COLUMN net_mac VARCHAR(20) NOT NULL DEFAULT '0' AFTER wireless_pan_id");
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        DB::raw('ALTER TABLE systems DROP COLUMN net_mac');
    }
}
