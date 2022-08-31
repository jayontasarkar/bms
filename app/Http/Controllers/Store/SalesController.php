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
		$totalSales = Sales::outletsWithDuePaymentsTotal($filter);

        $grandTotal = 0;
        $grandDiscount = 0;
        $totalSales->map(function($sale) use (&$grandTotal, &$grandDiscount) {
            $total = $sale->records->sum(function ($query) {
                return $query->unit_price * $query->qty;
            });
            $grandTotal += $total;
            $grandDiscount += $sale->total_discount;

            return $sale;
        });

    	return view('sales.index', compact('sales', 'grandTotal', 'grandDiscount'));
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
        $request->validate(['memo' => 'required|unique:sales,memo,' . $sales->id]);
        $sales->update(['memo' => $request->memo]);
        if ($sales->records) {
            $sales->truncateSalesOrder();
        }
        if($request->sales && count($request->sales)) {
            $sales->createRelationalData($request);
        }

        session()->flash('flash', $msg = 'Sales order updated');
        return response()->json(['msg' => $msg]);
    }

    public function destroy(Sales $sales)
    {
        if ($sales->records) {
            $sales->truncateSalesOrder();
        }
        $sales->forceDelete();

        session()->flash('flash', $msg = 'Sales order of memo #'. $sales->memo .' removed');
        return response()->json(['msg' => $msg]);
    }
}
