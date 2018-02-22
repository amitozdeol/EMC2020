<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomersTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('customers', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name')      ->nullable();
            $table->string('address1')  ->nullable();
            $table->string('address2')  ->nullable();
            $table->string('city')      ->nullable();
            $table->string('state')     ->nullable();
            $table->string('zip')       ->nullable();
            $table->string('email1')    ->nullable();
            $table->string('email2')    ->nullable();
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
