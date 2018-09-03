<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
    	'name', 'address', 'phone', 'opening_balance'
    ];

    public function purchases()
    {
    	return $this->hasMany(Purchase::class)->orderBy('created_at', 'desc');
    }
}
