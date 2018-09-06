<?php

use Illuminate\Database\Seeder;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Bank::create([
        	'name' => 'City Bank Limited',
        	'branch' => 'Dinajpur Sadar',
        	'account_no' => rand(55555, 888888888)
        ]);
        App\Models\Bank::create([
        	'name' => 'Dutch Bangla Bank Limited',
        	'branch' => 'Dinajpur Sadar',
        	'account_no' => rand(55555, 888888888)
        ]);
    }
}
