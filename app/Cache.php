<?php
use Illuminate\Database\Eloquent\Model;

class Cache extends Model
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
