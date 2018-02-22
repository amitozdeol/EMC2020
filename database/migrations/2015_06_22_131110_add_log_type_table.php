<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogTypeTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('log_type', function($table)
    {
      $table->increments('id');
      $table->string('name')->default('LOG_TYPE_DEFAULT_NAME');
      $table->string('description')->default('LOG_TYPE_DEFAULT_DESCRIPTION');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('log_type');
  }

}
