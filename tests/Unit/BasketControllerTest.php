<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

/**
 * Class BasketTest
 */
class BasketControllerTest extends TestCase //won't work with empty user table
{
    public function setUp()
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'ProductTableSeeder']);
    }

    /**
     * homepage index action test
     */
    public function testBasketAction()
    {
        $response = $this->get('/basket');
        $response->assertStatus(200);
    }

    /**
     *  testAddItem
     */
    public function testAddItem() //better to test after auth functionality will be added.
    {
        $productId = 1;

        $this->post(
            '/basket/add',
            [
                'productId' => $productId,
            ]
        );

        $this->assertDatabaseHas('products_basket', [
            'productId'       => $productId,
            'productQuantity' => 1,
        ]);

     }
}
