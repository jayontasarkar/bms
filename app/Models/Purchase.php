<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Product;
use App\Traits\Eloquent\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = [
    	'memo', 'vendor_id', 'total_balance', 'total_discount', 'purchase_date', 'total_paid', 'type'
    ];

    protected $dates = [ 'purchase_date' ];

    protected $with = [ 'vendor' ];

    public function setPurchaseDateAttribute($value)
    {
    	$this->attributes['purchase_date'] = Carbon::parse($value);
    }

    public function records()
    {
        return $this->morphMany(Record::class, 'recordable');
    }

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class);
    }

    public function transactions()
    {
        return $this->morphMany('App\Models\Transaction', 'transactionable');
    }

    public static function vendorsWithDuePayments($filter)
    {
    	$purchases = static::filter($filter)->orderBy('purchase_date', 'desc')
    	        ->with('vendor', 'records.product', 'transactions')
    	        ->get();
    	return $purchases->filter(function($query){
    		return ($query->total_balance - $query->total_discount - $query->total_paid) > 0;
    	});
    }

    public static function duePayments()
    {
    	$purchases = static::orderBy('purchase_date', 'desc')->with('vendor')->get();
    	return $purchases->filter(function($query){
    		return ($query->total_balance - $query->total_discount - $query->total_paid) > 0;
    	});
    }

    public function createRelationalData($request)
    {
        $purchases = $this->records()->createMany($request->only('purchases')['purchases']);
        foreach($purchases as $purchase) {
            $purchase->product->update([
                'stock' => $purchase->product->stock + $purchase->qty,
                'unit_price' => $purchase->unit_price
            ]);
        }
        return $this;
    }

    public function truncatePurchaseOrder()
    {
        foreach ($this->records as $record) {
            $product = Product::find($record->product_id);
            $product->update([
                'stock' => $product->stock - $record->qty
            ]);
        }
        return $this->records()->forceDelete();
    }
}
