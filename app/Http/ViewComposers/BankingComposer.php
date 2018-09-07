<?php 

namespace App\Http\ViewComposers;

use App\Models\{Bank, Transaction};
use Illuminate\View\View;

class BankingComposer
{
	public function compose(View $view)
	{
		$banks = Bank::orderBy('name')->with('transactions')->get();
		$transactions = Transaction::where('transactionable_type', 'App\Models\Bank')
		        ->orderBy('transaction_date', 'desc')->with('transactionable')->take(5)->get();
		
		$view->with('banks', $banks)->with('transactions', $transactions);
	}

}