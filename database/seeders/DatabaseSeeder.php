<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if(User::where('username', 'walker')->exists()){
            return;
        }

        $this->call(UserSeeder::class);
        $this->call(ProductCategoryAndProductSeeder::class);
        $this->call(ProductImageSeeder::class);
    }

}
