<?php 

namespace App\Http\ViewComposers;

use App\Models\District;
use Illuminate\View\View;

class UpozilaComposer
{
	public function compose(View $view)
	{
		$districts = District::orderBy('name')->select('id', 'name', 'slug')->with('thanas.outlets')->get();

		$view->with('districts', $districts);
	}

}