<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class DashboardItem extends Model
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
