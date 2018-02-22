<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlertsTypes extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        DB::raw('ALTER TABLE alerts ADD COLUMN email CHAR(1) NOT NULL DEFAULT 0 AFTER system_id');
        DB::raw('ALTER TABLE alerts ADD COLUMN sms CHAR(1) NOT NULL DEFAULT 0 AFTER email');
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        DB::raw('ALTER TABLE alerts DROP COLUMN email');
        DB::raw('ALTER TABLE alerts DROP COLUMN sms');
    }
}
