<?php 

namespace App\Http\Filters;

use Carbon\Carbon;

class ExpenseFilter extends QueryFilter
{
	public function days($days)
	{
		return $this->builder->where('expense_date', '>=', now()->subDays($days)->startOfDay());
	}

	public function months($months)
	{
		return $this->builder->where('expense_date', '>=', now()->subMonths($months)->startOfDay());
	}

	public function from($date)
	{
		return $this->builder->where('expense_date', '>=', Carbon::parse($date)->startOfDay());
	}

	public function to($date)
	{
		return $this->builder->where('expense_date', '<=', Carbon::parse($date)->endOfDay());
	}	

	public function vendor($vendor)
	{
		if($vendor == 'other') {
			return $this->builder->where('vendor_id', null);
		}
		return $this->builder->where('vendor_id', $vendor);
	}
}