<?php 

namespace App\Http\ViewComposers;

use App\Models\Product;
use Illuminate\View\View;

class ProductComposer
{
	public function compose(View $view)
	{
		$vendor = request('vendor', null);
		$query = (new Product())->newQuery();
		if ($vendor) {
			$query->where('vendor_id', $vendor);
		}
		$products = $query->orderBy('title')->with('vendor')->get();

		$view->with('products', $products);
	}

}