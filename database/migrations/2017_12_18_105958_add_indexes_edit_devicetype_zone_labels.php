<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesEditDevicetypeZoneLabels extends Migration
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
            $table->index(['system_id', 'date', 'id', 'command']);
        });

        Schema::table('device_data', function ($table) {
            $table->index(['system_id', 'date', 'id', 'command']);
        });

        Schema::table('device_data_hourly_ave', function ($table) {
            $table->primary('recnum');
            $table->index(['system_id', 'date', 'id', 'command']);
        });

        Schema::table('device_type', function ($table) {
            $table->integer('digital')->nullable()->after('reinstruct_on_restart');
        });
        DB::statement('ALTER TABLE device_types CHANGE COLUMN created_at created_at TIMESTAMP NULL DEFAULT NULL , CHANGE COLUMN updated_at updated_at TIMESTAMP NULL DEFAULT NULL');

        Schema::table('zone_labels', function ($table) {
            $table->string('temp_range')->nullable()->after('zonename');
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
            $table->dropIndex(['system_id', 'date', 'id', 'command']);
        });

        Schema::table('device_data', function ($table) {
            $table->dropIndex(['system_id', 'date', 'id', 'command']);
        });

        Schema::table('device_data_hourly_ave', function ($table) {
            $table->dropIndex(['system_id', 'date', 'id', 'command']);
        });
        Schema::table('device_type', function ($table) {
            $table->dropColumn('digital');
        });
        DB::statement('ALTER TABLE device_types CHANGE COLUMN created_at created_at TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');
        DB::statement('ALTER TABLE device_types CHANGE COLUMN updated_at updated_at TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        Schema::table('zone_labels', function ($table) {
            $table->dropColumn('temp_range');
        });
    }
}
