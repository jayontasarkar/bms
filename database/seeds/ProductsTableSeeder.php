<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker\Factory::create();
        $vendors = App\Models\Vendor::all();

        foreach($vendors as $key => $vendor) {
        	App\Models\Product::insert([
        		[
        			'code' => str_random(4) . $key,
        			'title' => $faker->unique()->sentence(4),
        			'vendor_id' => $vendor->id,
        			'stock'     => $faker->numberBetween(50, 100),
        			'created_at' => now()
        		],
        		[
        			'code' => str_random(4) . $key,
        			'title' => $faker->unique()->sentence(4),
        			'vendor_id' => $vendor->id,
        			'stock'     => $faker->numberBetween(50, 100),
        			'created_at' => now()
        		],
        		[
        			'code' => str_random(4) . $key,
        			'title' => $faker->unique()->sentence(4),
        			'vendor_id' => $vendor->id,
        			'stock'     => $faker->numberBetween(50, 100),
        			'created_at' => now()
        		]
        	]);
        }
    }
}
