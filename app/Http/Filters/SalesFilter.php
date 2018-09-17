<?php 

namespace App\Http\Filters;

use Carbon\Carbon;

class SalesFilter extends QueryFilter
{
	public function vendor($vendor)
	{
		if($vendor == 'all') {
			return $this->builder;
		}
		return $this->builder->where('vendor_id', $vendor);
	}
	public function from($date)
	{
		return $this->builder->where('purchase_date', '>=', Carbon::parse($date)->startOfDay());
	}

	public function to($date)
	{
		return $this->builder->where('purchase_date', '<=', Carbon::parse($date)->endOfDay());
	}	
}