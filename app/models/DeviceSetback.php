<?php

class DeviceSetback extends Eloquent {

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'device_setback';

  protected $primaryKey = 'recnum';

  public $timestamps = false;
}