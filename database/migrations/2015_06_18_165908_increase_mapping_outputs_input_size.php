<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseMappingOutputsInputSize extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        DB::statement('ALTER TABLE mapping_output MODIFY COLUMN inputs TEXT');
        DB::statement('ALTER TABLE mapping_output MODIFY COLUMN reserveinputs TEXT');
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        DB::statement('ALTER TABLE mapping_output MODIFY COLUMN inputs VARCHAR(100)');
        DB::statement('ALTER TABLE mapping_output MODIFY COLUMN reserveinputs VARCHAR(100)');
    }
}
