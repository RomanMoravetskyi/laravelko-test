<?php

namespace App\Http\Controllers;

use App\Basket;
use App\Product;
use App\User;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class HomepageController
 */
class HomepageController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        $allProducts = Product::all();
        $basket = Basket::where('userId', User::DEFAULT_TESTING_USER_ID)->first();

        return view(
            'homepage',
            [
                'allProducts' => $allProducts,
                'basketItemsCount' => empty($basket) ? 0 : $basket->getAttribute('quantity'),
            ]
        );
    }
}