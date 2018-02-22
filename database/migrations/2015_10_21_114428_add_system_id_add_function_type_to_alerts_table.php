<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSystemIdAddFunctionTypeToAlertsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::table('alerts', function ($table) {
            $table->integer('system_id')->after('building_id')->nullable();
            $table->integer('alarm_code')->after('class_subscription')->nullable();
            $table->string('function', 50)->after('alarm_code')->nullable();
            $table->string('notification_type', 10)->after('system_id')->default('alarm');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::table('alerts', function ($table) {
            $table->dropColumn('system_id');
            $table->dropColumn('alarm_code');
            $table->dropColumn('function');
            $table->dropColumn('notification_type');
        });
    }
}
