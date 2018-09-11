<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    /**
     * @var string
     */
    public $table = 'basket';

    /**
     * @var array
     */
    protected $fillable = ['quantity', 'userId', 'price'];
}
