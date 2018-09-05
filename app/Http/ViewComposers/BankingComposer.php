<?php 

namespace App\Http\ViewComposers;

use App\Models\Bank;
use Illuminate\View\View;

class BankingComposer
{
	public function compose(View $view)
	{
		$banks = Bank::orderBy('name')->get();

		$view->with('banks', $banks);
	}

}