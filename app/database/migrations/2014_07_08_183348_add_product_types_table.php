<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductTypesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product_types', function($table)
    {
      $table->increments('recnum');
      $table->integer('product_id'); // unique product type number
      $table->timestamps();
      $table->string('name', 45)->nullable();
      $table->string('product_type', 20)->nullable(); // class of products ie sensor, relay, 
      $table->string('function', 45)->nullable(); 
      $table->string('mode', 10)->nullable(); // input or output
      $table->string('manufacturer',50)->nullable();
      $table->string('partnumber',50)->nullable(); 
      $table->string('commands', 20)->nullable();  // supported commands in device types table
      $table->string('hardwarebus', 15)->nullable(); // wired, wireless, bacnetmstp, bacnetether
      $table->string('direct', 5)->nullable();  // directly connected to EMC or thru an alternate controller  - EMC or AUX
      $table->string('reporttime',10)->default("5");   // default report time in minutes  default for wireless is 5 minutes  COV for report on Change of Value
      $table->integer('powerlevel')->default(0);  // set to 2's complement of power level default to 0
      $table->string('auxcontroller', 50)->nullable();  // Name of Auxiliary Controller
      $table->string('price', 10)->nullable();
      $table->string('comments', 100)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('product_types');
  }

}
