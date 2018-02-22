<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersPasswordResetToken extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function($table)
    {
        $table->string('password_reset_token', 32)->nullable()->after('password');
        $table->datetime('password_reset_expires')->nullable()->after('password_reset_token');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('users', function($table)
    {
      $table->dropColumn('password_reset_token');
      $table->dropColumn('password_reset_expires');
    });
  }

}
