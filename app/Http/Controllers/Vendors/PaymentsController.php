<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index(Request $request, Vendor $vendor)
    {
    	$query = (new Transaction)->newQuery();
    	$query->where('type', false)
    	      ->where('transactionable_id', $vendor->id)
    	      ->where('transactionable_type', Vendor::class);
    	if($request->has('from') && $request->has('to')) {
    		$from = Carbon::parse($request->from);
    		$to   = Carbon::parse($request->to);
    		$query->where('type', false)
    		      ->whereBetween('transaction_date', [$from, $to]);
    	}
    	$query->orderBy('transaction_date', 'desc');
    	$payments = $query->get();

    	return view('vendors.payments.index', compact('payments', 'vendor'));
    }

    public function store(Request $request, Vendor $vendor)
    {
    	$vendor->transactions()->create($request->only('amount', 'transaction_date', 'comment'));

    	session()->flash('flash', $msg = 'New payment added for vendor');
    	return response()->json(['msg' => $msg]);
    }

    public function update(Request $request, Transaction $transaction)
    {
    	$transaction->update($request->only('amount', 'transaction_date', 'comment'));

    	session()->flash('flash', $msg = 'Existing payment updated for vendor');
    	return response()->json(['msg' => $msg]);
    }

    public function destroy(Transaction $transaction)
    {
    	$transaction->forceDelete();
    	session()->flash('flash', $msg = 'Existing payment removed for vendor');
    	return response()->json(['msg' => $msg]);
    }
}
