<?php
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
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
