<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(LaratrustSeeder::class);
        $user = \App\Models\User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'super@weka.com',
            'password' => bcrypt('12345678'),

        ]);

        $user->attachRole('super_admin');

        $category = Category::create(
            [
                'name'=>'Category Test 1'
            ]
        );

        $category2 = Category::create(
            [
                'name'=>'Category Test 2'
            ]
        );

        $product = Product::create(
            [
                'name'=>'Product Test',
                'description'=>'Product Test',
                'stock'=>20,
                'sale_price'=>20,
                'purchase_price'=>10,
                'category_id'=>1,   

            ]
        );

        $product = Product::create(
            [
                'name'=>'Product Test 2',
                'description'=>'Product Test 2',
                'stock'=>20,
                'sale_price'=>20,
                'purchase_price'=>10,
                'category_id'=>2,   

            ]
        );

        $client = Client::create([
            'name'=>'Aly Client',
            'phone'=>['01115881178'],
            'address'=>'dokki street',

        ]);
    }
}
