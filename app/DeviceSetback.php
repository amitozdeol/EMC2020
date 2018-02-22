<?php
use Illuminate\Database\Eloquent\Model;

class DeviceSetback extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'device_setback';

    protected $primaryKey = 'recnum';

    public $timestamps = false;
}
