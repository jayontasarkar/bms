<?php 

namespace App\Services;

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

		return $result;
	}
}