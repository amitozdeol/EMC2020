<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildingsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('buildings', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->softDeletes();
      $table->integer('customer_id');
      $table->string('name', 200);
      $table->string('address1', 250);
      $table->string('address2', 250);
      $table->string('city', 200);
      $table->string('state', 45);
      $table->integer('zip');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('buildings');
  }

}
