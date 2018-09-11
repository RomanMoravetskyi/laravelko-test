<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsBasket extends Model
{
    /**
     * @var string
     */
    public $table = 'products_basket';

    /**
     * @var array
     */
    protected $fillable = ['productId', 'basketId', 'productQuantity'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
