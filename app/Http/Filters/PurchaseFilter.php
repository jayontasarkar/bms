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

	public function search($value)
	{
		return $this->builder->where('memo', 'LIKE', "%$value%");
	}

	public function vendor($value)
	{
		return $this->builder->where('vendor_id', $value);
	}
}