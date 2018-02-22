<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceData extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'device_data';

    protected $primaryKey = 'recnum';
}
