<?php
namespace App\Services;

use App\ProductsBasket;

/**
 * Interface BasketServiceInterface
 */
Interface BasketServiceInterface
{
    /**
     * @param ProductsBasket[] $productsBasket
     *
     * @return array
     */
    public function getBasketProductsDataByProductsBasket($productsBasket): array;

    /**
     * @param integer $productId
     * @param integer $userId
     *
     * @return bool
     */
    public function addBasketItem($productId, $userId): bool;

    /**
     * @param integer $productId
     * @param integer $userId
     *
     * @return bool
     */
    public function removeBasketItem($productId, $userId): bool;
}