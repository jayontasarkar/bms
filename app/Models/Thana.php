<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thana extends Model
{
	use SoftDeletes;
	
    protected $fillable = [ 'name', 'district_id' ];

    public function district()
    {
    	return $this->belongsTo(District::class);
    }

    public function outlets()
    {
    	return $this->hasMany(Outlet::class)->orderBy('name');
    }
}
