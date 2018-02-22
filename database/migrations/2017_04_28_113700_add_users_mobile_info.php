<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersMobileInfo extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::raw("ALTER TABLE users ADD COLUMN mobile_number VARCHAR(12) NOT NULL DEFAULT '0000000000' AFTER email");
    DB::raw("ALTER TABLE users ADD COLUMN mobile_carrier VARCHAR(40) NOT NULL DEFAULT '@vtext.com' AFTER mobile_number");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::raw('ALTER TABLE users DROP COLUMN mobile_carrier');
    DB::raw('ALTER TABLE users DROP COLUMN mobile_number');
  }

}
