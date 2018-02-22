<?php

class DashboardItem extends Eloquent
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'dashboard_items';


  /**
   * [$fillable description]
   * @var array
   */
    protected $fillable = ['label', 'order', 'parent_id', 'system_id', 'chart_type'];
}
