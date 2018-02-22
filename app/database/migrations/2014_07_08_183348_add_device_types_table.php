<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceTypesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {  // used for the definition of multi sensors associated with a product type
    Schema::create('device_types', function($table)
    {  // used for the definition of multi sensors associated with a product type
      $table->increments('recnum');
      $table->integer('id'); /// links to product type table
      $table->timestamps();
      $table->string('name', 45)->nullable();
      $table->integer('command');  // type of sensor message 1 = Temp, 2 voltage, 3 motion, 4 light etc
      $table->string('function', 45)->nullable();
      $table->string('units', 20)->nullable();
      $table->string('mode', 10)->nullable();  // Wireless/Wired/BacNET
      $table->string('IO', 10)->nullable();  // Input or Output
      $table->decimal('hysteresis', 8, 3)->default(0);
      $table->string('powerlevel', 10)->nullable();
      $table->string('reporttime', 10)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('device_types');
  }

}
