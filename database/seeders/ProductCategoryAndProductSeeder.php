<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategoryAndProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = \Faker\Factory::create();

        $productCats = array();
        for ($i = 0; $i < 5; $i++) {
            $productCats[$i] =
                ProductCategory::create([
                    'category_name' => $faker->words(2, true),
                ]);
        }

        for ($i = 0; $j = 0, $i < 50; $i++) {

            if($j > 4){
                $j = 0;
            }

            Product::create([
                'product_name' => $faker->word(),
                'category' => $productCats[$j]->getAttribute('category_name'),
                'stock' => $faker->numberBetween(10, 100),
                'sales_price' => $faker->randomFloat(2, 100, 10000),
                'cost_price' => $faker->randomFloat(2, 100, 10000),
                'unit_of_measurement' => $faker->word()
            ]);
        }

    }


}
