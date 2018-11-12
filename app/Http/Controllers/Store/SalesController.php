<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Filters\SalesFilter;
use App\Http\Requests\SalesFormRequest;
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
        $sales->load('outlet.thana.district', 'records');

    	return view('sales.show', compact('sales'));
    }

    public function store(SalesFormRequest $request)
    {
    	$sale = Sales::create(
    		$request->only('memo', 'outlet_id', 'vendor_id', 'total_discount', 'sales_date', 'comment')
    	);
    	$sale->createRelationalData($request);

    	session()->flash('flash', $msg = 'Sales order created successfully');
    	return response()->json(['msg' => $msg]);
    }

    public function update(Request $request, Sales $sales)
    {
        if($sales->records) {
            foreach($sales->records as $record) {
                $record->product->update([
                    'stock' => $record->product->stock + $record->qty
                ]);
            }
        }
        $sales->records()->forceDelete();
        if($request->sales && count($request->sales)) {
            $sales->createRelationalData($request);
        }

        session()->flash('flash', $msg = 'Sales order updated');
        return response()->json(['msg' => $msg]);
    }
}
