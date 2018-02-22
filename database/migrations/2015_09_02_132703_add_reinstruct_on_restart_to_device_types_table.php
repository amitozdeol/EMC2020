<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReinstructOnRestartToDeviceTypesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('device_types', function ($table) {
            $table->integer('reinstruct_on_restart')->default(0);
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
            $table->dropColumn('reinstruct_on_restart');
        });
    }
}
