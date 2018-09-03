<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use App\Http\Requests\SalesFormRequest;
use Illuminate\Http\Request;

class SalesController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth']);
	}

    public function store(SalesFormRequest $request)
    {
    	$sale = Sales::create(
    		$request->only('memo', 'outlet_id', 'total_balance', 'total_discount', 'sales_date')
    	);
    	$sale->records()->createMany($request->only('sales')['sales']);

    	session()->flash('flash', $msg = 'Sales order created successfully');
    	return response()->json(['msg' => $msg]);
    }
}
