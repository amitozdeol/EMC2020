<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDashboardItemsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('dashboard_items', function($table)
    {
      $table->increments('id');
      $table->timestamps();
      $table->string('label');
      $table->string('order');
      $table->integer('parent_id');
      $table->integer('system_id');
      $table->string('chart_type')->nullable();
    });

    DashboardItem::create(array(
      'id' => 1,
      'label' => 'Dashboard',
      'order' => 1,
      'parent_id' => 0,
      'system_id' => 0
    ));

    DashboardItem::create(array(
      'id' => 2,
      'label' => 'Operations',
      'order' => 2,
      'parent_id' => 0,
      'system_id' => 0
    ));

    DashboardItem::create(array(
      'id' => 3,
      'label' => 'Zone Status',
      'order' => 3,
      'parent_id' => 0,
      'system_id' => 0
    ));

    DashboardItem::create(array(
      'id' => 4,
      'label' => 'Alarms',
      'order' => 4,
      'parent_id' => 1,
      'system_id' => 0,
    ));

    DashboardItem::create(array(
      'id' => 5,
      'label' => 'Device Status',
      'order' => 5,
      'parent_id' => 2,
      'system_id' => 0,
    ));



    DashboardItem::create(array(
      'label' => '',
      'order' => 1,
      'parent_id' => 1,
      'system_id' => 0,
      'chart_type' => 'FURNACE'
    ));

    DashboardItem::create(array(
      'label' => 'Temperature',
      'order' => 1,
      'parent_id' => 2,
      'system_id' => 0,
      'chart_type' => 'Temperature'
    ));
    DashboardItem::create(array(
      'label' => 'Humidity',
      'order' => 2,
      'parent_id' => 2,
      'system_id' => 0,
      'chart_type' => 'Humidity'
    ));
    DashboardItem::create(array(
      'label' => 'Pressure Differential',
      'order' => 3,
      'parent_id' => 2,
      'system_id' => 0,
      'chart_type' => 'Pressure Differential'
    ));

    DashboardItem::create(array(
      'label' => '',
      'order' => 1,
      'parent_id' => 3,
      'system_id' => 0,
      'chart_type' => 'ZONE'
    ));

    DashboardItem::create(array(
      'label' => '',
      'order' => 1,
      'parent_id' => 4,
      'system_id' => 0,
      'chart_type' => 'ALARM'
    ));

    DashboardItem::create(array(
      'label' => '',
      'order' => 1,
      'parent_id' => 5,
      'system_id' => 0,
      'chart_type' => 'DEVICE'
    ));


  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('dashboard_items');
  }

}
