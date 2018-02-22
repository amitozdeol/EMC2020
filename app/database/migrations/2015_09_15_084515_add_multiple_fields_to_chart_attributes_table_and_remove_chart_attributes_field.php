<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleFieldsToChartAttributesTableAndRemoveChartAttributesField extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('chart_attributes', function($table) 
    {
      $table->dropColumn([
        'attributes',
        'devices',
      ]);
    });
    
    Schema::table('chart_attributes', function($table) 
    {
      $table->string('title',50);
      $table->string('chart_type_1',50);
      $table->string('chart_type_2',50)->nullale();
      $table->string('function_type_1',50);
      $table->string('function_type_2',50)->nullale();
      $table->string('chart_label_1',50);
      $table->string('chart_label_2',50)->nullale();
      $table->text('devices_1',500);
      $table->text('devices_2',500)->nullable();
      $table->string('x_axis_1',50)->nullable();
      $table->string('x_axis_2',50)->nullable();
      $table->string('y_axis_1',50);
      $table->string('y_axis_2',50)->nullable();
      $table->integer('x_range_1')->nullable();
      $table->integer('x_range_2')->nullable();
      $table->text('description',100)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('chart_attributes', function($table) 
    {
      $table->dropColumn([
        'title',
        'chart_type_1',
        'chart_type_2',
        'function_type_1',
        'function_type_2',
        'chart_label_1',
        'chart_label_2',
        'devices_1',
        'devices_2',
        'x_axis_1',
        'x_axis_2',
        'y_axis_1',
        'y_axis_2',
        'x_range_1',
        'x_range_2',
        'description'
      ]);
    });

    Schema::table('chart_attributes', function($table)
    {
      $table->text('devices',500);
      $table->text('attributes',500);
    });
  }

}
