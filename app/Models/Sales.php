<?php

namespace App\Models;

use App\Traits\Eloquent\Filterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = [
    	'memo', 'outlet_id', 'total_balance', 'total_discount', 'sales_date', 'total_paid', 'type', 'comment'
    ];

    protected $dates = [ 'sales_date' ];

    protected $with = ['outlet'];

    public function setSalesDateAttribute($value)
    {
    	$this->attributes['sales_date'] = Carbon::parse($value);
    }

    public function records()
    {
    	return $this->hasMany(SalesRecord::class, 'sale_id', 'id');
    }

    public function outlet()
    {
    	return $this->belongsTo(Outlet::class);
    }

    public static function outletsWithDuePayments($filter)
    {
    	$sales = static::filter($filter)->orderBy('sales_date', 'desc')
    	        ->with('outlet', 'records.product', 'transactions')->get();
    	return $sales->filter(function($query){
    		return ($query->total_balance - $query->total_discount - $query->total_paid) > 0;
    	});
    }

    public static function duePayments()
    {
    	$sales = static::orderBy('sales_date', 'desc')->with('outlet')->get();
    	return $sales->filter(function($query){
    		return ($query->total_balance - $query->total_discount - $query->total_paid) > 0;
    	});
    }

    public function getOpeningsAttribute()
    {
        return $this->transactions->where('type', true);
    }
}
