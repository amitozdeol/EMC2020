<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesForSystems extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('systems', function ($table) {
            $table->softDeletes()->after('updated_at');
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
            $table->dropSoftDeletes();
        });
    }
}
