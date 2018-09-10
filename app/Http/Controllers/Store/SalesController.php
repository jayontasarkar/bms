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
        $sales->load('outlet.thana.district', 'records', 'transactions');

    	return view('sales.show', compact('sales'));
    }

    public function store(SalesFormRequest $request)
    {
    	$sale = Sales::create(
    		$request->only('memo', 'outlet_id', 'total_balance', 'total_discount', 'sales_date', 'comment')
    	);
    	$sales = $sale->records()->createMany($request->only('sales')['sales']);
    	foreach($sales as $sale) {
    		$product = Product::find($sale->product_id);
    		$product->update(['stock' => $product->stock - $sale->qty ]);
    	}

    	session()->flash('flash', $msg = 'Sales order created successfully');
    	return response()->json(['msg' => $msg]);
    }

    public function update(Request $request, Sales $sales)
    {
        if($sales->records) {
            foreach($sales->records as $record) {
                $product = Product::find($record->product_id);
                $product->update(['stock' => $product->stock + $record->qty]);
            }
        }
        $sales->records()->forceDelete();
        if($request->sales && count($request->sales)) {
            foreach($request->only('sales')['sales'] as $sale) {
                $records = $sales->records()->create($sale);
                $records->product->update(['stock' => $records->product->stock - $sale['qty']]);
            }
        }
        $amount = $sales->fresh()->records->sum(function($query){
            return $query->unit_price * $query->qty;
        });
        $sales->update([
            'total_balance' => $amount, 
            'total_discount' => $request->input('total_discount'),
            'sales_date' => $request->input('sales_date')
        ]);

        session()->flash('flash', $msg = 'Sales order updated');
        return response()->json(['msg' => $msg]);
    }
}
