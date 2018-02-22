<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNetworkSettingsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('network_settings', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->softDeletes();
      $table->integer('system_id', 11)      ->default(0)->nullable();
      $table->string('static_ip', 20)       ->default(0)->nullable();
      $table->string('netmask', 20)         ->default(0)->nullable();
      $table->string('gateway', 20)         ->default(0)->nullable();
      $table->string('dns_nameserver', 20)  ->default(0)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('network_settings');
  }

}
