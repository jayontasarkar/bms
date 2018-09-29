<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorFormRequest;
use App\Models\Vendor;
use Carbon\Carbon;
use Excel;
use PDF;
use App\Exports\VendorExport;
use Illuminate\Http\Request;

class VendorsController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index()
    {
    	$vendors = Vendor::orderBy('name')->get();

    	return view('vendors.index', compact('vendors'));
    }

    public function store(VendorFormRequest $request)
    {
    	$vendor = Vendor::create($request->only('name', 'address', 'phone'));
        $vendor->transactions()->create([
            'comment' => 'OPENING BALANCE',
            'amount'  => $request->has('amount') ? $request->amount : 0,
            'type'    => 1,
            'transaction_date' => now()
        ]);
    	session()->flash('flash', $msg = 'Vendor created successfully');
    	return response()->json(['msg' => $msg]);
    }

    public function show(Vendor $vendor)
    {
    	$result = $this->generateRelationalData($vendor);
    	
    	return view('vendors.show', compact('result'));
    }

    public function update(VendorFormRequest $request, Vendor $vendor)
    {
    	$vendor->update($request->all());
        $vendor->createOrUpdateOpeningBalance($request);

    	session()->flash('flash', $msg = 'Vendor updated successfully');
    	return response()->json(['msg' => $msg]);
    }

    public function destroy(Vendor $vendor)
    {
    	$vendor->delete();

    	session()->flash('flash', $msg = 'Vendor deleted successfully');
    	return response()->json(['msg' => $msg]);
    }

    public function excel()
    {
        $vendor = Vendor::find(request('id'));
        $results = $this->generateRelationalData($vendor);
        $vendorRelationalData = $results->purchases->sortByDesc('created_at');
        return Excel::download(new VendorExport($vendorRelationalData), str_slug($vendor->name). '-' . time() . '.xlsx');
    }

    public function pdf()
    {
        $vendor = Vendor::find(request('id'));
        $vendorResults = $this->generateRelationalData($vendor);

        $pdf = PDF::loadView('export.pdf.vendor', compact('vendorResults'));

        return $pdf->download( str_slug($vendor->name) . '-' . time() . '.pdf');
    }

    private function generateRelationalData($vendor)
    {
        if(request()->has('month') && request()->has('year')) {
            return $vendor->load(['purchases' => function($query){
                $start = Carbon::parse(request('year') . '-' . request('month') . '-01')->startOfDay();
                $end   = Carbon::parse(request('year') . '-' . request('month') . '-01')->endOfMonth();
                $query->whereBetween('purchase_date', [$start, $end]);
                $query->with('transactions', 'records.product');
            }]);
        }
        if(request()->has('from') && request()->has('to')) {
            return $vendor->load(['purchases' => function($query){$start = Carbon::parse(request('from'))->startOfDay();
                $end   = Carbon::parse(request('to'))->endOfDay();
                $query->whereBetween('purchase_date', [$start, $end]);
                $query->with('transactions', 'records.product');
            }]);
        }

        return $vendor->load('purchases.records.product', 'purchases.transactions');
    }
}
