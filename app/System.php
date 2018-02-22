<?php
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use SoftDeletes;

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'systems';

    protected $dates = ['deleted_at'];
}
