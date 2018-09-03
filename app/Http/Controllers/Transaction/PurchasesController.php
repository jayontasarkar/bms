<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index()
    {
    	return view('purchase.index');
    }

    public function store(Purchase $purchase, Request $request)
    {
    	$purchase->update(['total_paid' => ($purchase->total_paid + $request->amount)]);
    	$purchase->transactions()->create($request->all());

    	session()->flash('flash', $msg = 'Payment confirmed to purchase order: ' . $purchase->memo);
    	return response()->json(['msg' => $msg]);
    }
}
