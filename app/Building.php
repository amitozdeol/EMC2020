<?php
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use SoftDeletes;

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'buildings';

    protected $dates = ['deleted_at'];
}
