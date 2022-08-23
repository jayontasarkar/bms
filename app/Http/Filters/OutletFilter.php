<?php 

namespace App\Http\Filters;

use Carbon\Carbon;

class OutletFilter extends QueryFilter
{
	public function district($id)
	{
		return $this->builder->whereHas('thana', function($query) use ($id) {
			$query->where('district_id', $id);
		});
	}

	public function thana($id)
	{
		return $this->builder->where('thana_id', $id);
	}

	public function search($query)
	{
		return $this->builder->where('name', 'LIKE', "%$query%");
	}
}