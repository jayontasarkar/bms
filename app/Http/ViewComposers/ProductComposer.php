<?php 

namespace App\Http\ViewComposers;

use App\Models\Product;
use Illuminate\View\View;

class ProductComposer
{
	public function compose(View $view)
	{
		$products = Product::orderBy('title')->with('vendor')->get();

		$view->with('products', $products);
	}

}