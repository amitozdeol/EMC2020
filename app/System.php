<?php

class System extends Eloquent
{
    use SoftDeletingTrait;

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'systems';

    protected $dates = ['deleted_at'];
}
