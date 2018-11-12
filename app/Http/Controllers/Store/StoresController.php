<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\{Purchase, Sales, ReadySale};
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index()
    {
    	$purchases = Purchase::with('records')->select('id', 'memo', 'created_at')->get();
        $sales     = Sales::with('records')->select('id', 'memo', 'created_at')->get();
    	$readySales     = ReadySale::with('records')->select('id', 'memo', 'created_at')->get();
    	$merged = $sales->merge($purchases ?: collect())->merge($readySales ?: collect());
        $latestSalesAndPurhases = $merged->sortByDesc(function($obj, $key) {
            return $obj->created_at;
        })->take(35);

    	return view('store.index', compact('latestSalesAndPurhases'));
    }
}
