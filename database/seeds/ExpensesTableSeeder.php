<?php

use Faker\Factory;
use Illuminate\Database\Seeder;

class ExpensesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $users = App\Models\User::pluck('id')->toArray();

        for($i = 0; $i <= 50; $i++) {
        	App\Models\Expense::create([
        		'title' => $faker->sentence($faker->numberBetween(2, 4)),
        		'description' => $faker->paragraphs(3, true),
        		'amount' => round_balance($faker->numberBetween(1000, 25000)),
        		'expense_date' => now()->subDays($faker->numberBetween(1, 60)),
        		'user_id'  => $faker->randomElement($users),
                'vendor_id' => $faker->numberBetween(1, 2)
        	]);
        }
    }
}
