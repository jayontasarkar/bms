<?php

namespace App\Http\Controllers\Outlet;

use App\Exports\OutletExport;
use App\Http\Controllers\Controller;
use App\Http\Filters\OutletFilter;
use App\Http\Requests\OutletFormRequest;
use App\Models\Outlet;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PDF;

class OutletsController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index(OutletFilter $filters)
    {
    	$outlets = Outlet::filter($filters)->orderBy('name')->with('thana.district.thanas', 'sales')
                          ->get();

    	return view('outlet.index', compact('outlets'));
    }

    public function show(Outlet $outlet)
    {
        $outlet->load('thana.district');

        $this->generateRelationalData($outlet);
        
        return view('outlet.show', compact('outlet'));
    }

    public function store(OutletFormRequest $request)
    {
    	$outlet = Outlet::create($request->only('name', 'proprietor', 'phone', 'address', 'thana_id'));
        if($request->has('hasOpeningBalance')) {
            $outlet->sales()->create($request->only('memo', 'total_balance', 'type', 'sales_date'));
        }

    	session()->flash('flash', $msg = 'New Outlet created successfully');
    	return response()->json(['msg' => $msg]);
    }

    public function update(OutletFormRequest $request, Outlet $outlet)
    {
        $outlet->update($request->all());

        session()->flash('flash', $msg = 'Outlet updated successfully');
        return response()->json(['msg' => $msg]);
    }

    public function excel()
    {
        $outlet = Outlet::find(request('id'));
        $results = $this->generateRelationalData($outlet);
        $outletRelationalData = $results->sales->sortByDesc('created_at');
        return Excel::download(new OutletExport($outletRelationalData), str_slug($outlet->name). '-' . time() . '.xlsx');
    }

    public function pdf()
    {
        $outlet = Outlet::find(request('id'));
        $outletResults = $this->generateRelationalData($outlet);

        $pdf = PDF::loadView('export.pdf.outlet', compact('outletResults'));

        return $pdf->download( str_slug($outlet->name) . '-' . time() . '.pdf');
    }

    private function generateRelationalData($outlet)
    {
        if(request()->has('month') && request()->has('year')) {
            return $outlet->load(['sales' => function($query){
                $start = Carbon::parse(request('year') . '-' . request('month') . '-01')->startOfDay();
                $end   = Carbon::parse(request('year') . '-' . request('month') . '-01')->endOfMonth();
                $query->whereBetween('sales_date', [$start, $end]);
                $query->with('records.product');
            }]);
        }
        if(request()->has('from') && request()->has('to')) {
            return $outlet->load(['sales' => function($query){
                $start = Carbon::parse(request('from'))->startOfDay();
                $end   = Carbon::parse(request('to'))->endOfDay();
                $query->whereBetween('sales_date', [$start, $end]);
                $query->with('records.product');
            }]);
        }

        return $outlet->load('sales.records.product');
    }
}
