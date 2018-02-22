<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('customer_id');
            $table->string('email', 200);
            $table->string('password', 200);
            $table->string('remember_token', 100)->nullable();
            $table->string('prefix', 10);
            $table->string('first_name', 45);
            $table->string('middle_initial', 2);
            $table->string('last_name', 45);
            $table->string('suffix', 45);
            $table->integer('role')->default(0);
            $table->boolean('active')->default(1);
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
