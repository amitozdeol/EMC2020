<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSystemNames extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('systems', function ($table) {
            $table->string('name', 255)->after('updated_at')->nullable();
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
            $table->dropColumn('name');
        });
    }
}
