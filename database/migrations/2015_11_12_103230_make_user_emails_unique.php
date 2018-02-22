<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeUserEmailsUnique extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        DB::raw('ALTER TABLE users ADD UNIQUE (email)');
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        DB::raw('ALTER TABLE users DROP INDEX email');
    }
}
