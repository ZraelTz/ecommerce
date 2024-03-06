<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    public function run(): void
    {

        $faker = \Faker\Factory::create();
        $password = \Hash::make('$Password123');

        $user =  User::create([
            'username' => 'walker',
            'email' => 'user@test.com',
            'password' => $password,
            'phone'=> '2341234567890',
        ]);

        Customer::create([
            'user_id' => $user->getKey(),
            'address' => $faker->address
        ]);

        for ($i = 0; $i < 9; $i++) {
            $user = User::create([
                'username' => $faker->userName,
                'email' => $faker->email,
                'password' => $password,
                'phone' => $faker->phoneNumber,
            ]);

            Customer::create([
                'user_id' => $user->getKey(),
                'address' => $faker->address
            ]);
        }
    }
    
}