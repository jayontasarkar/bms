<?php

namespace App\Models;

use App\Traits\Eloquent\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
	use Filterable, SoftDeletes;
	
    protected $fillable = [
    	'name', 'proprietor', 'phone', 'address', 'thana_id'
    ];

    public function thana()
    {
    	return $this->belongsTo(Thana::class);
    }

    public function sales()
    {
    	return $this->hasMany(Sales::class);
    }
}
