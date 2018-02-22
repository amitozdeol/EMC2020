<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceDataCurrent extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'device_data_current';

    protected $primaryKey = 'recnum';
}
