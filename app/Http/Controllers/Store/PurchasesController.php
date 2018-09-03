<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseFormRequest;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function store(PurchaseFormRequest $request)
    {
    	$purchase = Purchase::create(
    		$request->only('memo', 'vendor_id', 'total_balance', 'total_discount', 'purchase_date')
    	);
    	$purchase->records()->createMany($request->only('purchases')['purchases']);

    	session()->flash('flash', $msg = 'Purchase order created successfully');
    	return response()->json(['msg' => $msg]);
    }
}
