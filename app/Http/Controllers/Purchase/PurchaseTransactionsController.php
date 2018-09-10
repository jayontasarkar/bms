<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseTransactionsController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function update(Request $request, Purchase $purchase)
    {
    	$purchase->transactions()->forceDelete();
    	if($request->transactions && count($request->transactions)) {
    		foreach($request->only('transactions')['transactions'] as $transaction) {
    			$purchase->transactions()->create($transaction);
    		}
    	}
    	$amount = $purchase->transactions->sum('amount');
    	$purchase->update(['total_paid' => $amount]);

    	session()->flash('flash', $msg = 'Purchase order transaction updated');
    	return response()->json(['msg' => $msg]);
    }
}
