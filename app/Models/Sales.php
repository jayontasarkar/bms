<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'memo', 'outlet_id', 'total_balance', 'total_discount', 'sales_date', 'total_paid'
    ];

    protected $dates = [ 'sales_date' ];

    public function setSalesDateAttribute($value)
    {
    	$this->attributes['sales_date'] = Carbon::parse($value);
    }

    public function records()
    {
    	return $this->hasMany(SalesRecord::class, 'sale_id', 'id');
    }

    public function transactions()
    {
        return $this->morphMany('App\Models\Transaction', 'transactionable');
    }

    public function outlet()
    {
    	return $this->belongsTo(Outlet::class);
    }

    public static function duePayments()
    {
    	$sales = static::orderBy('sales_date', 'desc')->with('outlet')->get();
    	return $sales->filter(function($query){
    		return ($query->total_balance - $query->total_discount - $query->total_paid) > 0;
    	});
    }
}
