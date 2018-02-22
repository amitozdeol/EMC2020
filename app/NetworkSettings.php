<?php
use Illuminate\Database\Eloquent\Model;

class NetworkSettings extends Model
{
    use SoftDeletes;

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'network_settings';

    protected $dates = ['deleted_at'];
}
