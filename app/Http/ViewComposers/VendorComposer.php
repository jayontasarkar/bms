<?php 

namespace App\Http\ViewComposers;

use App\Models\Vendor;
use Illuminate\View\View;

class VendorComposer
{
	public function compose(View $view)
	{
		$vendors = Vendor::orderBy('name')->get();

		$view->with('vendors', $vendors);
	}

}