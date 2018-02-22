<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLostInputsFieldToWebMappingTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('mapping_output', function($table)
    {
        $table->text('lost_inputs')->after('reserveinputs')->default('');
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
      $table->dropColumn('lost_inputs');
    });
  }

}
