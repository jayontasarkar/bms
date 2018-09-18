<?php

namespace App\Http\Controllers\Outlet;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CollectionsController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $query = (new Transaction)->newQuery();
        if(request()->has('vendor')) {
            $query->where('vendor_id', request('vendor'));
        }
        if(request()->has('from') && request()->has('to')) {
            $from = Carbon::parse(request('from'));
            $to   = Carbon::parse(request('to'));
            $query->whereBetween('transaction_date', [$from, $to]);
        }
        $collections = $query->whereIn('transactionable_type', ['App\Models\Outlet', 'App\Models\ReadySale'])
                    ->where('type', false)->with('vendor')->latest()->get();

        return view('collections.index', compact('collections'));            
    }

    public function show(Outlet $outlet)
    {
    	$outlet->load('thana');
    	$query = (new Transaction)->newQuery();
    	if(request()->has('vendor')) {
    		$query->where('vendor_id', request('vendor'));
    	}
    	if(request()->has('from') && request()->has('to')) {
    		$from = Carbon::parse(request('from'));
    		$to   = Carbon::parse(request('to'));
    		$query->whereBetween('transaction_date', [$from, $to]);
    	}
    	$collections = $query->where('transactionable_id', $outlet->id)
    	            ->where('transactionable_type', Outlet::class)
    	            ->where('type', false)->with('vendor')->latest()->get();
    	return view('collections.show', compact('outlet', 'collections'));
    }

    public function store(Request $request)
    {
    	$outlet = Outlet::find($request->outlet_id);

    	$outlet->transactions()->create($request->all());

    	session()->flash(
    		'flash', $msg = 'Collection added amount ' . number_format($request->amount) . '/= for outlet ' . $outlet->name
    	);
    	return response()->json(['msg' => $msg]);
    }

    public function edit(Transaction $transaction)
    {
    	$transaction->load('transactionable.thana');

    	return view('collections.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
    	$transaction->update($request->only('transactionable_id', 'vendor_id', 'amount', 'transaction_date'));

    	session()->flash(
    		'flash', $msg = 'Collection amount updated to ' . number_format($request->amount) . '/='
    	);
    	return response()->json(['msg' => $msg]);
    }

    public function destroy(Transaction $transaction)
    {
    	$transaction->delete();

    	session()->flash(
    		'flash', $msg = 'Collection item removed from storage '
    	);
    	return response()->json(['msg' => $msg]);
    }
}
