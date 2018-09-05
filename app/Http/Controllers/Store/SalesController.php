<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Filters\SalesFilter;
use App\Http\Requests\SalesFormRequest;
use App\Models\Product;
use App\Models\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth']);
	}

	public function index(SalesFilter $filter)
	{
		$sales = Sales::outletsWithDuePayments($filter);
    	
    	return view('sales.index', compact('sales'));
	}

	public function show(Sales $sales)
    {
    	return view('sales.show', compact('sales'));
    }

    public function store(SalesFormRequest $request)
    {
    	$sale = Sales::create(
    		$request->only('memo', 'outlet_id', 'total_balance', 'total_discount', 'sales_date')
    	);
    	$sales = $sale->records()->createMany($request->only('sales')['sales']);
    	foreach($sales as $sale) {
    		$product = Product::find($sale->product_id);
    		$product->update(['stock' => $product->stock - $sale->qty ]);
    	}

    	session()->flash('flash', $msg = 'Sales order created successfully');
    	return response()->json(['msg' => $msg]);
    }
}
