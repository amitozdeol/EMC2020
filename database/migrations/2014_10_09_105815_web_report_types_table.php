<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WebReportTypesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('web_reports_objects_types', function ($table) {
            $table->increments('recnum');
            $table->datetime('datetime');
            $table->integer('object_id');
            $table->text('object_url', 200);
            $table->text('object_title', 50);
            $table->text('object_parameters_format', 500);
            $table->text('input_parameters_format', 100);
            $table->boolean('active');
        });
    }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
    public function down()
    {
        Schema::dropIfExists('web_reports_objects_types');
    }
}
