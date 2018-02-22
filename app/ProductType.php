<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
    protected $table = 'product_types';

    protected $primaryKey = 'recnum';
}
