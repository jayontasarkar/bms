<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Illuminate\Http\Request;

class SalesTransactionsController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function update(Request $request, Sales $sales)
    {
    	$sales->transactions()->forceDelete();
    	if($request->transactions && count($request->transactions)) {
    		foreach($request->only('transactions')['transactions'] as $transaction) {
    			$sales->transactions()->create($transaction);
    		}
    	}
    	$amount = $sales->transactions->sum('amount');
    	$sales->update(['total_paid' => $amount]);

    	session()->flash('flash', $msg = 'Sales order transaction updated');
    	return response()->json(['msg' => $msg]);
    }
}
