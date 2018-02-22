<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDownlinkToSystemsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('systems', function ($table) {
            $table->boolean('downlink')->default(0)->after('active_inputs4');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::table('systems', function ($table) {
            $table->dropColumn('downlink');
        });
    }
}
