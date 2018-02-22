<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZoneToMappingOutputTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('mapping_output', function ($table) {
            $table->integer('zone')->default(0)->after('duration');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::table('mapping_output', function ($table) {
            $table->dropColumn('zone');
        });
    }
}
