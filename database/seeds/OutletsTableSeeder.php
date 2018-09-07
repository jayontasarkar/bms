<?php

use Faker\Factory;
use Illuminate\Database\Seeder;

class OutletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Factory::create();
        $thanas = App\Models\Thana::pluck('id')->toArray();
        foreach ($thanas as $thana) {
        	for($i = 0; $i <=5; $i++) {
        		App\Models\Outlet::create([
	        		'name' => $faker->company,
	        		'proprietor' => $faker->name,
	        		'phone'  => $faker->e164PhoneNumber,
	        		'address' => $faker->streetName,
	        		'thana_id' => $thana
	        	]);
        	}
        }
    }
}
