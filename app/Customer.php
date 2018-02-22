<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{

    use SoftDeletes;

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'customers';

    protected $dates = ['deleted_at'];
}
