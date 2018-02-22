<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class DeviceType extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'device_types';

    protected $primaryKey = 'recnum';
}
