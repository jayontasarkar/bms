<?php

use Illuminate\Database\Seeder;

class VendorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Vendor::insert([
        	[
	        	'name' => 'Gulf Oil Bangladesh Ltd'
	        ],
	        [
	        	'name' => 'High Power'
	        ]
        ]);
    }
}
