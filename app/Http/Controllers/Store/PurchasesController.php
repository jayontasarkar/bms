<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Filters\PurchaseFilter;
use App\Http\Requests\PurchaseFormRequest;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index(PurchaseFilter $filter)
    {
    	$purchases = Purchase::vendorsWithDuePayments($filter);
    	
    	return view('purchases.index', compact('purchases'));
    }

    public function show(Purchase $purchase)
    {
    	return view('purchases.show', compact('purchase'));
    }

    public function store(PurchaseFormRequest $request)
    {
    	$purchase = Purchase::create(
    		$request->only('memo', 'vendor_id', 'total_balance', 'total_discount', 'purchase_date')
    	);
    	$purchases = $purchase->records()->createMany($request->only('purchases')['purchases']);
    	foreach($purchases as $purchase) {
    		$product = Product::find($purchase->product_id);
    		$product->update(['stock' => $product->stock + $purchase->qty ]);
    	}
    	session()->flash('flash', $msg = 'Purchase order created successfully');
    	return response()->json(['msg' => $msg]);
    }
}
