<?php

class Cache extends Eloquent
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'cache';

    protected $primaryKey = 'key';

    public $timestamps = false;
}
