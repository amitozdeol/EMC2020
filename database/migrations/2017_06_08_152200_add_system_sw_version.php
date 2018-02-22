<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSystemSwVersion extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        DB::raw("ALTER TABLE systems ADD COLUMN software_version VARCHAR(20) NOT NULL DEFAULT '0' AFTER hardware_version");
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        DB::raw('ALTER TABLE systems DROP COLUMN software_version');
    }
}
