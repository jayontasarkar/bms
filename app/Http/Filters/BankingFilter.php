<?php 

namespace App\Http\Filters;

use Carbon\Carbon;

class BankingFilter extends QueryFilter
{
	public function bank($id)
	{
		return $this->builder->where('id', $id);
	}

	public function from($date)
	{
		$from = Carbon::parse($date)->startOfDay();
		$to   = Carbon::parse(request('to'))->endOfDay();
		return $this->builder
		        ->whereHas('transactions', function($query) use ($from, $to) {
		        	$query->whereBetween('transaction_date', [$from, $to]);
		        });
	}
}