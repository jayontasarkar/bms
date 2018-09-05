<?php

namespace App\Models;

use App\Traits\Eloquent\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use Filterable, SoftDeletes;
	
    protected $fillable = [
    	'code', 'title', 'vendor_id', 'stock', 'unit'
    ];

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class);
    }

    public function purchases()
    {
    	return $this->hasMany(PurchaseRecords::class, 'product_id')->orderBy('created_at', 'desc');
    }

    public function sales()
    {
    	return $this->hasMany(SalesRecord::class, 'sale_id')->orderBy('created_at', 'desc');
    }
}
