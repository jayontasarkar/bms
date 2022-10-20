<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Filters\OutletFilter;
use App\Models\District;
use App\Models\Outlet;
use App\Models\Thana;
use App\Models\Vendor;
use PDF;
use Illuminate\Http\Request;

class StoreReportsController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index(OutletFilter $filters)
    {
        $vendor = request('vendor', null);
    	$outlets = Outlet::filter($filters)->orderBy('name')
                        ->with([
                            'thana.district.thanas', 
                            'transactions' => function($query) use ($vendor) {
                                if ($vendor) {
                                    return $query->where('vendor_id', $vendor);
                                }
                                return $query;
                            }, 
                            'sales.records'
                        ])
                        ->paginate(config('bms.items_per_page'));
        $result = '';                
        if(request()->has('vendor')) {
    		$result .= '(' . Vendor::find(request('vendor'))->name . ') ';
    	}                
        $result .= "Report of outlets: ";
        if(request()->has('thana')) {
            $result .= Thana::find(request('thana'))->name . ' thana, ';
        }
        if(request()->has('district')) {
            $result .= District::find(request('district'))->name . ' district';
        }  
        $result .= " (Total {$outlets->total()} outlets)";

        $totalOutlets = Outlet::filter($filters)->orderBy('name')
            ->with([
                'thana.district.thanas',
                'transactions' => function ($query) use ($vendor) {
                    if ($vendor) {
                        return $query->where('vendor_id', $vendor);
                    }
                    return $query;
                },
                'sales.records'
            ])
            ->get();

        $grandTotalOpening = 0;
        $grandTotalSale = 0;
        $grandTotalPaid = 0;
        $grandTotalDue = 0;
        $totalOutlets->map(function($outlet) use (&$grandTotalOpening, &$grandTotalSale, &$grandTotalPaid, &$grandTotalDue) {
            $opening = $outlet->transactions->where('type', true)->sum('amount');
            $collections = $outlet->transactions->where('type', false)->sum('amount');
            $sale = $outlet->totalSalesByVendor(request('vendor') ?: false);
            $due = $opening + $sale - $collections;
            $grandTotalOpening += $opening;
            $grandTotalSale += $sale;
            $grandTotalPaid += $collections;
            $grandTotalDue += $due;

            return $outlet;
        });

        return view('store.report.index', compact(
            'outlets', 
            'result', 
            'grandTotalOpening', 
            'grandTotalSale', 
            'grandTotalPaid',
            'grandTotalDue'
        ));
    }
    
    public function exportToPdf(OutletFilter $filters)
    {
        $vendor = request('vendor', null);
    	$outlets = Outlet::filter($filters)->orderBy('name')
                        ->with([
                            'thana.district.thanas', 
                            'transactions' => function($query) use ($vendor) {
                                if ($vendor) {
                                    return $query->where('vendor_id', $vendor);
                                }
                                return $query;
                            }, 
                            'sales.records'
                        ])
                        ->get();
        $result = '';                
        if(request()->has('vendor')) {
    		$result .= '(' . Vendor::find(request('vendor'))->name . ') ';
    	}                
        $result .= "Report of outlets: ";
        if(request()->has('thana')) {
            $result .= Thana::find(request('thana'))->name . ' thana, ';
        }
        if(request()->has('district')) {
            $result .= District::find(request('district'))->name . ' district';
        }  
        $result .= " (Total {$outlets->count()} outlets)";

        $totalOutlets = Outlet::filter($filters)->orderBy('name')
            ->with([
                'thana.district.thanas',
                'transactions' => function ($query) use ($vendor) {
                    if ($vendor) {
                        return $query->where('vendor_id', $vendor);
                    }
                    return $query;
                },
                'sales.records'
            ])
            ->get();

        $grandTotalOpening = 0;
        $grandTotalSale = 0;
        $grandTotalPaid = 0;
        $grandTotalDue = 0;
        $totalOutlets->map(function($outlet) use (&$grandTotalOpening, &$grandTotalSale, &$grandTotalPaid, &$grandTotalDue) {
            $opening = $outlet->transactions->where('type', true)->sum('amount');
            $collections = $outlet->transactions->where('type', false)->sum('amount');
            $sale = $outlet->totalSalesByVendor(request('vendor') ?: false);
            $due = $opening + $sale - $collections;
            $grandTotalOpening += $opening;
            $grandTotalSale += $sale;
            $grandTotalPaid += $collections;
            $grandTotalDue += $due;

            return $outlet;
        });

        $pdf = PDF::loadView('export.pdf.store-report', compact(
            'outlets',
            'result',
            'grandTotalOpening',
            'grandTotalSale',
            'grandTotalPaid',
            'grandTotalDue'
        ));
        $name = "store-report-" . date('Y') . '-' . date('m') . '-' . date('d') . '.pdf';
        return $pdf->download($name);
    }
}
