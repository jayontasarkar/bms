<?php 

namespace App\Http\ViewComposers;

use App\Models\Outlet;
use Illuminate\View\View;

class OutletComposer
{
	public function compose(View $view)
	{
		$outlets = Outlet::orderBy('name')->get();

		$view->with('outlets', $outlets);
	}

}