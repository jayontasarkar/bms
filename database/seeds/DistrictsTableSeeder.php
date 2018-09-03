<?php

use App\Models\Thana;
use Illuminate\Database\Seeder;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	foreach(config('location') as $district) {
    		$dist = App\Models\District::create([
        		'name' => $district['name'],
        		'slug' => $district['slug'],
        	]);
        	foreach($district['thana'] as $thana) {
        		Thana::create([
        			'name' => $thana,
        			'district_id' => $dist->id
        		]);
        	}
    	}
    }
}
