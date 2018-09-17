<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
	use SoftDeletes;

    protected $fillable = [
    	'recordable_id', 'recordable_tyoe', 'product_id', 'unit_price', 'qty', 'unit'
    ];

    public function recordable()
    {
        return $this->morphTo();
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
}
