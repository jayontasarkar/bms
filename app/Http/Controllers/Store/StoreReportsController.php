<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Filters\OutletFilter;
use App\Models\District;
use App\Models\Outlet;
use App\Models\Thana;
use App\Models\Vendor;
use Illuminate\Http\Request;

class StoreReportsController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index(OutletFilter $filters)
    {
    	$outlets = Outlet::filter($filters)->orderBy('name')
                        ->with('thana.district.thanas', 'transactions', 'sales.records')
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

    	return view('store.report.index', compact('outlets', 'result'));
    }
}
