<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationsSentToSystemLog extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('system_log', function ($table) {
            $table->integer('notifications_sent')->after('report')->default(0);
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::table('system_log', function ($table) {
            $table->dropColumn('notifications_sent');
        });
    }
}
