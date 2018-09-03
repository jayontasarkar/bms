<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProductFilter;
use App\Http\Requests\ProductFormRequest;
use App\Models\{Product, SalesRecord, PurchaseRecords, Vendor};
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth']);
    }

    public function index(ProductFilter $filter)
    {
    	$vendors = Vendor::get();
    	$products = Product::orderBy('vendor_id')->filter($filter)->with('vendor')->get();
    	if(request()->has('vendor')) {
    		$filtered = $vendors->filter(function($value, $key){
    			return $value->id == request('vendor');
    		})->first();
    		$search = "Products of '" . $filtered->name . "'";
    	}else{
    		$search = 'List of all products';
    	}

    	return view('products.index', compact('vendors', 'products', 'search'));
    }

    public function show(Product $product)
    {
    	$sales = SalesRecord::byProduct($product);
    	$purchases = PurchaseRecords::byProduct($product);
    	$results = $sales->merge($purchases)->sortByDesc('created_at');
    
    	return view('products.show', compact('product', 'results'));
    }

    public function store(ProductFormRequest $request)
    {
    	$product = Product::create($request->all());

    	session()->flash('flash', $msg = 'Product created successfully');
    	return response()->json(['msg' => $msg]);
    }

    public function update(ProductFormRequest $request, Product $product)
    {
    	$product->update($request->all());

    	session()->flash('flash', $msg = 'Product updated successfully');
    	return response()->json(['msg' => $msg]);
    }
}
