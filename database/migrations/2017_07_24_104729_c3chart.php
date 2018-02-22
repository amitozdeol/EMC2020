<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class C3chart extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('device_data_long_term', function ($table) {
                $table->dropColumn('conversion');
            $table->unsignedInteger('unix_date')->nullable()->after('alarm_index');
                $table->date('date')->nullable()->after('unix_date');
        });

        Schema::table('device_data', function ($table) {
                $table->dropColumn('conversion');
            $table->unsignedInteger('unix_date')->nullable()->after('alarm_index');
                $table->date('date')->nullable()->after('unix_date');
        });

        Schema::table('device_data_current', function ($table) {
                $table->unsignedInteger('unix_date')->nullable()->after('alarm_index');
                $table->date('date')->nullable()->after('unix_date');
        });

        Schema::create('device_data_hourly_ave', function ($table) {
            $table->increments('recnum');
                $table->datetime('date_time');
                $table->unsignedInteger('id');
                $table->unsignedInteger('system_id');
                $table->unsignedInteger('command');
                $table->float('current_value');
                $table->float('setpoint');
                $table->unsignedInteger('unix_date');
                $table->date('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('device_data_long_term', function ($table) {
            $table->dropColumn(['unix_date', 'date']);
                $table->float('conversion');
        });

        Schema::table('device_data', function ($table) {
            $table->dropColumn(['unix_date', 'date']);
                $table->float('conversion');
        });

        Schema::table('device_data_current', function ($table) {
            $table->dropColumn(['unix_date', 'date']);
        });

        Schema::dropIfExists('device_data_hourly_ave');
    }
}
