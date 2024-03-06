<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $productImages = [
            "https://images.pexels.com/photos/2894285/pexels-photo-2894285.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
            "https://images.pexels.com/photos/277262/pexels-photo-277262.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
            "https://images.pexels.com/photos/1527010/pexels-photo-1527010.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
            "https://images.pexels.com/photos/2694393/pexels-photo-2694393.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
            "https://images.pexels.com/photos/4033046/pexels-photo-4033046.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load",
            "https://images.pexels.com/photos/3609872/pexels-photo-3609872.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load"
        ];

        $faker = \Faker\Factory::create();

        for ($i=1; $i < 51; $i++) { 
            ProductImage::create([
                'product_id' => $i,
                'image_url' => $productImages[$faker->numberBetween(0, 5)],
            ]);

            ProductImage::create([
                'product_id' => $i,
                'image_url' => $productImages[$faker->numberBetween(0, 5)],
            ]);

            ProductImage::create([
                'product_id' => $i,
                'image_url' => $productImages[$faker->numberBetween(0, 5)],
            ]);
        }
    }


}
