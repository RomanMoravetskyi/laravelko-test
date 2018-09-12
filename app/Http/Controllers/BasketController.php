<?php

namespace App\Http\Controllers;

use App\Basket;
use App\User;
use App\ProductsBasket;
use App\Services\BasketServiceInterface;
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
     * @var BasketServiceInterface
     */
    protected $basketService;

    /**
     * @param DiscountServiceInterface $discountService
     * @param BasketServiceInterface $basketService
     */
    public function __construct(DiscountServiceInterface $discountService, BasketServiceInterface $basketService)
    {
        $this->discountService = $discountService;
        $this->basketService = $basketService;
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

        if ($basket) {
            $productsBasket = ProductsBasket::where('basketId', $basket->id)->get();
            $discount = $this->discountService->calculateDiscount($basket->price, $productsBasket, $user);
            $productData = $this->basketService->getBasketProductsDataByProductsBasket($productsBasket);
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

        $this->basketService->addBasketItem($productId, $userId);

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

       $this->basketService->removeBasketItem($productId, $userId);

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