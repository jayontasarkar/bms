<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function store(Sales $sales, Request $request)
    {
    	$sales->update(['total_paid' => ($sales->total_paid + $request->amount)]);
    	$sales->transactions()->create($request->all());

    	session()->flash('flash', $msg = 'Amount confirmed for Sales order: ' . $sales->memo);
    	return response()->json(['msg' => $msg]);
    }
}
