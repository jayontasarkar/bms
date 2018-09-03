<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'memo', 'vendor_id', 'total_balance', 'total_discount', 'purchase_date', 'total_paid'
    ];

    protected $dates = [ 'purchase_date' ];

    public function setPurchaseDateAttribute($value)
    {
    	$this->attributes['purchase_date'] = Carbon::parse($value);
    }

    public function records()
    {
    	return $this->hasMany(PurchaseRecords::class, 'purchase_id', 'id');
    }

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class);
    }

    public function transactions()
    {
        return $this->morphMany('App\Models\Transaction', 'transactionable');
    }

    public static function duePayments()
    {
    	$purchases = static::orderBy('purchase_date', 'desc')->with('vendor')->get();
    	return $purchases->filter(function($query){
    		return ($query->total_balance - $query->total_discount - $query->total_paid) > 0;
    	});
    }
}
