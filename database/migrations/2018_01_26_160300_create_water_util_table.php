<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaterUtilTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utility', function ($table) {
            $table->increments('recnum');
            $table->integer('device_id');
            $table->integer('system_id');
            $table->string('user', 45)->nullable();
            $table->string('token', 45)->nullable();
            $table->integer('bbl')->nullable();
            $table->decimal('acc_no', 10, 2)->nullable();
            $table->string('mtr_no', 45)->nullable();
            $table->string('reg_id')->nullable();
        });

        // Insert row for water utility
        DB::table('device_types')->insert(
            [
                'id' => '46',
                'created_at' => '2018-01-23 03:42:00',
                'updated_at' => '2018-01-23 03:42:10',
                'name' => 'DEP AMR Daily Water Meter',
                'command' => '46',
                'function' => 'Water',
                'unit' => 'cu.ft',
                'mode' => 'api',
                'IO' => 'Input',
                'hysteresis' => '1.000',
                'setpoint' => '1500.000',
                'alarm_high' => '2500.000',
                'alarm_low' => '1000.000',
                'powerlevel' => '0',
                'reporttime' => '1440',
                'setpoint_active' => '1',
                'algorithm_active' => '1',
                'gain' => '1.0000',
                'intercept' => '0.0000',
                'state_above_setpoint' => '0',
                'reinstruct_on_restart' => '0',
                'digital' => '0'
                
            ]
        );

        // Insert row for water utility
        DB::table('product_types')->insert(
            [
                'product_id' => 'U1',
                'created_at' => '2018-01-23 03:46:00',
                'updated_at' => '2018-01-23 03:46:30',
                'name' => 'DEP AMR Daily Water Meter',
                'product_type' => 'Sensor',
                'function' => 'Water',
                'mode' => 'Output',
                'manufacturer' =>'DEP',
                'partnumber' => 'n/a',
                'command' => '46',
                'hardwarebus' => 'api',
                'direct' => 'EMC',
                'reporttime' => '20',
                'powerlevel' => '0',
                'auxcontroller' => '-',
                'price' => '0',
                'comments' =>'Water monitoring'
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utility');
        
        DB::table('device_types')->where('id', '=', '46')->delete();
        DB::table('product_types')->where('product_id', '=', 'U1')->delete();
    }
}
