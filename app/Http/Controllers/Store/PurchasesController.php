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
        $purchase->load('vendor', 'records', 'transactions');

    	return view('purchases.show', compact('purchase'));
    }

    public function store(PurchaseFormRequest $request)
    {
    	$purchase = Purchase::create(
    		$request->only('memo', 'vendor_id', 'total_balance', 'total_discount', 'purchase_date')
    	);
    	$purchase->createRelationalData($request);

    	session()->flash('flash', $msg = 'Purchase order created successfully');
    	return response()->json(['msg' => $msg]);
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate(['memo' => 'required|unique:purchases,memo,' . $purchase->id]);
        if ($purchase->records) {
            $purchase->truncatePurchaseOrder();
        }
        if($request->purchases && count($request->purchases)) {
            foreach($request->only('purchases')['purchases'] as $pur) {
                $records = $purchase->records()->create($pur);
                $records->product->update([
                    'stock' => $records->product->stock + $pur['qty'],
                    'unit_price' => $pur['unit_price']
                ]);
            }
        }
        $amount = $purchase->fresh()->records->sum(function($query){
            return $query->unit_price * $query->qty;
        });
        $purchase->update([
            'total_balance' => $amount,
            'total_discount' => $request->input('total_discount'),
            'purchase_date' => $request->input('purchase_date')
        ]);

        session()->flash('flash', $msg = 'Purchase order updated');
        return response()->json(['msg' => $msg]);
    }

    public function destroy(Purchase $purchase)
    {
        if($purchase->records) {
            $purchase->truncatePurchaseOrder();
        }
        $purchase->forceDelete();

        session()->flash('flash', $msg = 'Purchase order of memo #' . $purchase->memo . ' removed');
        return response()->json(['msg' => $msg]);
    }
}
