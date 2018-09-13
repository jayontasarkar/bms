<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
    	'name', 'address', 'phone'
    ];

    public function purchases()
    {
    	return $this->hasMany(Purchase::class)->orderBy('created_at', 'desc');
    }

    public function products()
    {
    	return $this->hasMany(Product::class);
    }
}
