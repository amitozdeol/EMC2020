<?php

class Zone extends Eloquent
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'zone_labels';

    protected $primaryKey = 'recnum';

    public $timestamps = false;
}
