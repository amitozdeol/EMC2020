<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveInputsFieldToMappingOutputsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('mapping_output', function($table)
    {
        $table->text('active_inputs')->after('reserveinputs')->default('');
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
      $table->dropColumn('active_inputs');
    });
  }

}
