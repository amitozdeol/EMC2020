<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPanidToSystemsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('systems', function ($table) {
            $table->integer('wireless_pan_id')->after('wireless_port')->default(0);
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
            $table->dropColumn('wireless_pan_id');
        });
    }
}
