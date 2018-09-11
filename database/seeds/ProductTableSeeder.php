<?php

use App\Product;
use Illuminate\Database\Seeder;

/**
 * Class ProductTableSeeder
 */
class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productUniqueDescription = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores error eum inventore officia quis quos totam! Asperiores deleniti, distinctio illum incidunt nulla officiis quas suscipit vitae? Magni necessitatibus repellendus voluptate!';

        $data = [
            [
                'name'        => 'Becoming a manager',
                'imagePath'   => 'https://i.pinimg.com/originals/0e/3e/71/0e3e71f0d02da6dd38e5d2b5a54490e5.jpg',
                'description' => $productUniqueDescription,
                'price'       => 10,
            ],
            [
                'name'        => 'Turning Coffee Into Code',
                'imagePath'   => 'https://i.pinimg.com/originals/03/30/16/0330168f9f7b08a7b35c0553f27490f3.jpg',
                'description' => $productUniqueDescription,
                'price'       => 12
            ],
            [
                'name'        => 'Pasting Code From Random Tutorial',
                'imagePath'   => 'https://pbs.twimg.com/media/C1hNdR9XUAEtlmP.jpg',
                'description' => $productUniqueDescription,
                'price'       => 25
            ],
           [
                'name'        => 'Hoping this works',
                'imagePath'   => 'https://boyter.org/static/books/Image-uploaded-from-iOS.jpg',
                'description' => $productUniqueDescription,
                'price'       => 15
            ],

        ];

        //product seed
        foreach ($data as $values) {
            (new Product($values))->save();
        }
    }
}
