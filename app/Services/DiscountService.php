<?php
namespace App\Services;

use App\Product;
use App\ProductsBasket;
use App\User;

/**
 * Class DiscountService
 */
class DiscountService implements DiscountServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function calculateDiscount($basketPrice, $productsBasket, $user): float
    {
        $priceWithoutDiscount = $basketPrice;

        $basketPrice -= $this->calculateBogofDiscount($productsBasket);
        $basketPrice -= $this->calculateGreaterThanDiscount($basketPrice);
        $basketPrice -= $this->calculateLoyaltyDiscount($basketPrice, $user);

        $summeryDiscount = $priceWithoutDiscount - $basketPrice;

        return number_format($summeryDiscount, 2);
    }

    /**
     * @param ProductsBasket[] $productsBasket
     *
     * @return float
     */
    public function calculateBogofDiscount($productsBasket)
    {
        $discountSummary = 0;
        $bogofProductIds = $this->getBogofProductIds();

        foreach ($productsBasket as $productInBasket) {
            if (in_array($productInBasket->productId, $bogofProductIds)) { //search for discount
                $product = Product::find($productInBasket->productId);
                $discountSummary += floor($productInBasket->productQuantity / 2) * $product->price;
            }
        }

        return number_format($discountSummary, 2);
    }

    /**
     * @param float $basketPrice
     *
     * @return float
     */
    public function calculateGreaterThanDiscount($basketPrice)
    {
        $discountSummary = 0;
        $discountMockedData = $this->getTotalGreaterThanDiscountCondtions();

        if ($basketPrice > $discountMockedData['minBasketPrice']) {
             $discountSummary = $basketPrice * $discountMockedData['discountPercent'];
        }

        return number_format($discountSummary, 2);
    }

    /**
     * @param float $basketPrice
     * @param User  $user
     *
     * @return float
     */
    public function calculateLoyaltyDiscount($basketPrice, $user)
    {
        $discountSummary = 0;
        $discountLoyaltyPercent = $this->getLoyaltyCartDiscountPercent();

        if ($user->hasLoyaltyCard) {
            $discountSummary = $basketPrice * $discountLoyaltyPercent;
        }

        return number_format($discountSummary, 2);
    }

    /**
     * @return array
     */
    private function getTotalGreaterThanDiscountCondtions() //@todo should be located in db, discount table. mocked for now
    {
        return [
            'discountPercent' => 0.1,
            'minBasketPrice'  => 20,
        ];
    }

    /**
     * @return array
     */
    private function getBogofProductIds() //@todo should be located in db, discount table. mocked for now
    {
        return [ 1 ];
    }

    /**
     * @return float
     */
    private function getLoyaltyCartDiscountPercent() //@todo should be located in db, discount table. mocked for now
    {
        return 0.02;
    }
}