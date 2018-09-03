<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRecords extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'purchase_id', 'product_id', 'unit_price', 'qty', 'unit'
    ];

    public function purchase()
    {
    	return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public static function byProduct($product)
    {
    	return static::where('product_id', $product->id)->whereHas('purchase', function($query){
    		if(request()->has('from') && request()->has('to')) {
    			$from = Carbon::parse(request('from'));
    			$to = Carbon::parse(request('to'));
    			$query->whereBetween('purchase.purchase_date', [$from, $to]);
    		}
    	})->with('purchase.vendor')->get();
    }
}
