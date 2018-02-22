<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlarmsNotificationsSentField extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('alarms', function ($table) {
            $table->boolean('notifications_sent')->default(0)->after('description');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::table('alarms', function ($table) {
            $table->dropColumn('notifications_sent');
        });
    }
}
