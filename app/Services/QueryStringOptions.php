<?php 

namespace App\Services;

use App\Models\Vendor;
use Carbon\Carbon;

class QueryStringOptions
{
	public function get()
	{
		$request = request();
		
		$result = 'All results';

		if($request->has('days')) {
			$result = now()->subDays($request->days)->format('M d, Y') . ' - ' .
					  now()->format('M d, Y') . ' (' . $request->days . ' days)'  ;
		}

		if($request->has('months')) {
			$result = now()->subMonths($request->months)->format('M d, Y') . ' - ' .
					  now()->format('M d, Y') . ' (' . $request->months . ' months)'  ;
		}

		if($request->has('from') && $request->has('to')) {
			$result = '(' . Carbon::parse($request->from)->format('M d, Y') . ' - ' . 
						Carbon::parse($request->to)->format('M d, Y') . ')';
		}

		if($request->has('vendor')) {
			if($request->vendor != 'other')
				$result .= ' (' . Vendor::find(request('vendor'))->name . ')';
			else
				$result .= ' (Other Expenses)';
		}

		return $result;
	}
}