<?php
namespace App\Services;

use App\Basket;
use App\Product;
use App\ProductsBasket;

/**
 * Class DiscountService
 */
class BasketService implements BasketServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBasketProductsDataByProductsBasket($productsBasket): array
    {
        $productData = [];

        foreach ($productsBasket as $productBasket) {
            $productData[] = [
                'product' => Product::find($productBasket->productId), //@todo in future should be done via relations
                'amount'  => $productBasket->productQuantity
            ];
        }

        return $productData;
    }

   /**
    * {@inheritdoc}
    */
    public function addBasketItem($productId, $userId): bool
    {
        $product = Product::find($productId); //@todo should be added check for wrong productId
        $basket = Basket::where('userId', $userId)->first();

        if ($basket) {
            $basket->increment('quantity');
            $basket->price += $product->price;
            $basket->save();

            $productBasket = ProductsBasket::where('basketId', $basket->id)->where('productId', $product->id)->first();

            if (!empty($productBasket)) {
                $productBasket->increment('productQuantity');
            } else {
                $productBasket = new ProductsBasket(['productId' => $product->id, 'basketId' => $basket->id, 'productQuantity' => 1]); // product quantity hardcoded to 1, due to we can't buy more than 1 product per 1 add to basket
                $productBasket->save();
            }
        } else {
            $basket = new Basket(['quantity' => 1, 'userId' => $userId, 'price' =>$product->price]); //@todo add transaction.
            $basket->save();

            $productBasket = new ProductsBasket(['productId' => $product->id, 'basketId' => $basket->id, 'productQuantity' => 1]);
            $productBasket->save();
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function removeBasketItem($productId, $userId): bool
    {
        $basket = Basket::where('userId', $userId)->first();

        $productsBasket = ProductsBasket::where('basketId', $basket->id)->where('productId', $productId)->first(); //@todo better to add transactions
        $productsBasket->productQuantity -= 1;
        $productsBasket->save();

        if ($basket->quantity == 1) {
            Basket::where('userId', $userId)->delete();
        } else {
            $basket->quantity -= 1;
            $basket->price -= (Product::find($productId))->price;
            $basket->save();

            if($productsBasket->productQuantity == 0) {
                ProductsBasket::where('productId', $productId)->where('basketId', $basket->id)->delete();
            }
        }

        return true;
    }
}