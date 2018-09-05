<?php

namespace App\Http\Controllers\Banking;

use App\Http\Controllers\Controller;
use App\Http\Filters\BankingFilter;
use App\Models\{Bank, Transaction};
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function __construct()
	{
		$this->middleware(['auth']);
	}

	public function index(BankingFilter $filter)
	{
		$bankings = Bank::filter($filter)->with(['transactions' => function($query) {
				if(request()->has('from') && request()->has('to')) {
					$from = Carbon::parse(request('from'))->startOfDay();
					$to = Carbon::parse(request('to'))->endOfDay();
		        	$query->whereBetween('transaction_date', [$from, $to]);
				}
	        }])->get();
    	
    	return view('banking.index', compact('bankings'));
	}

	public function update(Request $request, Transaction $transaction)
	{
		$transaction->update($request->all());

		$msg = 'Bank transaction updated successfully';
		return response()->json(['msg' => $msg]);
	}

	public function store(Request $request)
	{
		$bank = Bank::find($request->bank);
		$bank->transactions()->create($request->only('comment', 'amount', 'transaction_date', 'type'));
		session()->flash('flash', 'Bank transaction adjusted successfully');
		return response()->json(['msg' => '']);
	}
}
