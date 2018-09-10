<?php

namespace App\Models;

use App\Models\SalesRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'sale_id', 'product_id', 'unit_price', 'qty', 'unit'
    ];

    public function sale()
    {
    	return $this->belongsTo(Sales::class, 'sale_id', 'id');
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public static function byProduct($product)
    {
    	return static::where('product_id', $product->id)->whereHas('sale', function($query){
    		if(request()->has('from') && request()->has('to')) {
    			$from = Carbon::parse(request('from'));
    			$to = Carbon::parse(request('to'));
    			$query->whereBetween('sales.sales_date', [$from, $to]);
    		}
    	})->with('sale.outlet')->get();
    }
}
