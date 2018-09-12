<?php
namespace App\Services;

use App\ProductsBasket;
use App\User;

/**
 * Interface DiscountServiceInterface
 */
Interface DiscountServiceInterface
{
    /**
     * @param float            $basketPrice
     * @param ProductsBasket[] $productsBasket
     * @param User             $user
     *
     * @return float
     */
    public function calculateDiscount($basketPrice,  $productsBasket, $user): float;
}