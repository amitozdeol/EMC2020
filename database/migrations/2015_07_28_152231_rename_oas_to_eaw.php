<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameOasToEaw extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        $customer = Customer::find(0);
        if (isset($customer)) {
            $customer->name = 'EAW Electronic Systems';
            $customer->save();
        }
        DB::statement("ALTER TABLE alarm_codes ALTER COLUMN alarm_class SET DEFAULT 'EAW';");
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        $customer = Customer::find(0);
        if (isset($customer)) {
            $customer->name = 'OAS';
            $customer->save();
        }

        DB::statement("ALTER TABLE alarm_codes ALTER COLUMN alarm_class SET DEFAULT 'OAS';");
    }
}
