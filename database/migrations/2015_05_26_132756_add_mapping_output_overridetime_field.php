<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMappingOutputOverridetimeField extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('mapping_output', function($table)
    {
        $table->integer('overridetime')->default(0)->after('override');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('mapping_output', function($table)
    {
      $table->dropColumn('overridetime');
    });
  }

}
