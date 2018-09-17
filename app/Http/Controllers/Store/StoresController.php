<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\{Purchase, Sales};
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index()
    {
    	$purchases = Purchase::select('id', 'memo', 'created_at')->get();
    	$sales     = Sales::select('id', 'memo', 'created_at')->get();
    	$latestSalesAndPurhases = $sales->union($purchases)->sortByDesc('created_at')->take(15);
    	
    	return view('store.index', compact('latestSalesAndPurhases'));
    }
}
