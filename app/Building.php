<?php

class Building extends Eloquent
{
    use SoftDeletingTrait;

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'buildings';

    protected $dates = ['deleted_at'];
}
