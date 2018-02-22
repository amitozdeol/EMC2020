<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSystemLogTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('system_log', function ($table) {
            $table->increments('recnum');
            $table->integer('system_id');
            $table->string('application_name')->default('SYSTEM_LOG_DEFAULT_APP');
            $table->string('report')->default('SYSTEM_LOG_DEFAULT_REPORT');
            $table->datetime('datetime');
            $table->integer('log_type');

            $table->index('system_id');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('system_log');
    }
}
