<?php

namespace App\Http\Controllers\ReadySale;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReadySaleFormRequest;
use App\Models\ReadySale;
use Illuminate\Http\Request;

class ReadySalesController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index()
    {
    	$query = (new ReadySale)->newQuery();
    	if(request()->has('vendor')) {
    		$query->where('vendor_id', request('vendor'));
    	}
    	if(request()->has('from') && request()->has('to')) {
    		$from = Carbon::parse(request('from'));
    		$to   = Carbon::parse(request('to'));
    		$query->whereBetween('ready_sale_date', [$from, $to]);
    	}
    	$readysales = $query->with('records.product', 'transactions')
			->latest()
			->paginate(config('bms.items_per_page'));

		$query2 = (new ReadySale)->newQuery();
		if (request()->has('vendor')) {
			$query2->where('vendor_id', request('vendor'));
		}
		if (request()->has('from') && request()->has('to')) {
			$from = Carbon::parse(request('from'));
			$to   = Carbon::parse(request('to'));
			$query2->whereBetween('ready_sale_date', [$from, $to]);
		}
		$readysales = $query2->with('records.product', 'transactions')
		->latest()
		->paginate(config('bms.items_per_page'));

    	return view('readysale.index', compact('readysales'));
    }

    public function store(Request $request)
    {
    	$readySale = ReadySale::create(
    		$request->only('memo', 'ready_sale_details', 'vendor_id', 'total_discount', 'ready_sale_date')
    	);
    	$readySale->insertRelationalData($request);

    	session()->flash('flash', $msg = 'Ready sale created successfully');
    	return response()->json(['msg' => $msg]);
    }

    public function edit(ReadySale $readySale)
    {
    	$readySale->load('records', 'transactions');
    	return view('readysale.edit', compact('readySale'));
    }

    public function update(ReadySaleFormRequest $request, ReadySale $readySale)
    {
    	$readySale->update([
    		'ready_sale_details' => $request->ready_sale_details,
    		'memo' => $request->memo,
    		'vendor_id' => $request->vendor_id,
    		'ready_sale_date' => $request->ready_sale_date,
    		'total_discount'  => $request->total_discount
    	]);
    	if($readySale->records) {
            foreach($readySale->records as $record) {
                $record->product->update([
                    'stock' => $record->product->stock + $record->qty
                ]);
            }
        }
        $readySale->records()->forceDelete();
        $readySale->transactions()->forceDelete();
        if($request->records && count($request->records)) {
            $readySale->insertRelationalData($request);
        }

        session()->flash('flash', $msg = 'Ready Sale order updated');
        return response()->json(['msg' => $msg]);
    }

    public function destroy(ReadySale $readySale)
    {
    	$readySale->transactions()->delete();
    	$readySale->records()->delete();
    	$readySale->delete();

    	session()->flash('flash', $msg = 'Ready sale removed successfully');
    	return response()->json(['msg' => $msg]);
    }
}
