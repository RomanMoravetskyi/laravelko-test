<?php

namespace App\Http\Controllers;

use App\Basket;
use App\Product;
use App\ProductsBasket;
use App\User;
use App\Services\DiscountServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class BasketController
 */
class BasketController extends BaseController
{
    /**
     * @var DiscountServiceInterface
     */
    protected $discountService;

    /**
     * @param DiscountServiceInterface $discountService
     */
    public function __construct(DiscountServiceInterface $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBasketData(Request $request)
    {
        $productData = [];
        $discount = 0;

        $user = $request->user() ?: User::find(User::DEFAULT_TESTING_USER_ID);
        $basket = Basket::where('userId', User::DEFAULT_TESTING_USER_ID)->first();

        if($basket) { //@todo move to separate service
            $productsBasket = ProductsBasket::where('basketId', $basket->id)->get();
            $discount = $this->discountService->calculateDiscount($basket->price, $productsBasket, $user);

            foreach ($productsBasket as $productBasket) {
                $productData[] = [
                    'product' => Product::find($productBasket->productId), //@todo in future should be done via relations
                    'amount'  => $productBasket->productQuantity
                ];
            }
        }

        return view(
            'basket',
            [
                'productData'      => $productData,
                'basketItemsCount' => empty($basket) ? 0 : $basket->quantity,
                'basketPrice'      => empty($basket) ? 0 : $basket->price - $discount,
                'discount'         => $discount,
            ]
        );

    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addItem(Request $request)
    {
        $productId = $request->input('productId');
        $user = $request->user();

        $userId = empty($user) ? User::DEFAULT_TESTING_USER_ID : $user->id;
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
            $basket = new Basket(['quantity' => 1, 'userId' => $userId, 'price' =>$product->price]); //@todo add transition.
            $basket->save();

            $productBasket = new ProductsBasket(['productId' => $product->id, 'basketId' => $basket->id, 'productQuantity' => 1]);
            $productBasket->save();
        }

        return redirect('/');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function removeItem(Request $request)
    {
       $productId = $request->input('productId');
       $user = $request->user();
       $userId = empty($user) ? User::DEFAULT_TESTING_USER_ID : $user->id;

       $basket = Basket::where('userId', $userId)->first();

       $productsBasket = ProductsBasket::where('basketId', $basket->id)->where('productId', $productId)->first(); //@todo better to add transactions
       $productsBasket->productQuantity -= 1;
       $productsBasket->save();

       if($basket->quantity == 1) { //@todo for future better to move this part into separate service
           Basket::where('userId', $userId)->delete();
       } else {
           $basket->quantity -= 1;
           $basket->price -= (Product::find($productId))->price;
           $basket->save();

           if($productsBasket->productQuantity == 0) {
               ProductsBasket::where('productId', $productId)->where('basketId', $basket->id)->delete();
           }
       }

       return redirect('/basket');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearBasket(Request $request)
    {
       $user = $request->user();
       $userId = empty($user) ? User::DEFAULT_TESTING_USER_ID : $user->id;

       Basket::where('userId', $userId)->delete();

       return redirect('/basket');
    }
}