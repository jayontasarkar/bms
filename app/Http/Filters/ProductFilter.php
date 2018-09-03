<?php 

namespace App\Http\Filters;

use Carbon\Carbon;

class ProductFilter extends QueryFilter
{
	public function vendor($vendor)
	{
		return $this->builder->where('vendor_id', $vendor);
	}	
}