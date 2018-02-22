<?php
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'alerts';

    public $timestamps = false;
}
