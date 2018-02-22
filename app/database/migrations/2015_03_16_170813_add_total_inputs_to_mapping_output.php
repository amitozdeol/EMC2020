<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalInputsToMappingOutput extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('mapping_output', function($table)
    {
      $table->integer('device_type')->after('function_type');
      $table->integer('total_inputs')->after('min_required_inputs');

      $table->dropColumn('logicmode');
    });

    Schema::table('mapping_output', function($table)
    {
      $table->integer('logicmode')->after('polarity')->default(0);
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
      $table->dropColumn('device_type');
      $table->dropColumn('total_inputs');

      $table->dropColumn('logicmode');
    });

    Schema::table('mapping_output', function($table)
    {
      $table->string('logicmode')->after('polarity');
    });
  }

}
