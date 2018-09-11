<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Class BasketTest
 */
class HomepageTest extends TestCase
{
    /**
     * homepage index action test
     */
    public function testIndexAction()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
     }
}
