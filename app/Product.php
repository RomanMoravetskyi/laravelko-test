<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 */
class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'imagePath', 'description', 'price'];
}
