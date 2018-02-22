<?php

class NetworkSettings extends Eloquent
{
    use SoftDeletingTrait;

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'network_settings';

    protected $dates = ['deleted_at'];
}
