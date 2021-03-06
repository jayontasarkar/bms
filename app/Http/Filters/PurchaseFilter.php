<?php 

namespace App\Http\Filters;

use Carbon\Carbon;

class PurchaseFilter extends QueryFilter
{
	public function from($date)
	{
		return $this->builder->where('purchase_date', '>=', Carbon::parse($date)->startOfDay());
	}

	public function to($date)
	{
		return $this->builder->where('purchase_date', '<=', Carbon::parse($date)->endOfDay());
	}	
}