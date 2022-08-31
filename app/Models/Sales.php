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
    	'memo', 'outlet_id', 'vendor_id', 'total_discount', 'sales_date', 'comment'
    ];

    protected $dates = [ 'sales_date' ];

    protected $with = ['outlet'];

    public function setSalesDateAttribute($value)
    {
    	$this->attributes['sales_date'] = Carbon::parse($value);
    }

    public function records()
    {
        return $this->morphMany(Record::class, 'recordable');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function outlet()
    {
    	return $this->belongsTo(Outlet::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public static function outletsWithDuePayments($filter)
    {
    	return static::filter($filter)->orderBy('sales_date', 'desc')
    	        ->with('outlet', 'records.product', 'vendor')
                ->paginate(config('bms.items_per_page'));
    }

    public static function outletsWithDuePaymentsTotal($filter)
    {
        return static::filter($filter)->orderBy('sales_date', 'desc')
        ->with('outlet', 'records.product', 'vendor')
        ->get();
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

    public function createRelationalData($request)
    {
        $sales = $this->records()->createMany($request->only('sales')['sales']);
        foreach($sales as $sale) {
            $sale->product->update([
                'stock' => $sale->product->stock - $sale->qty
            ]);
        }
        return $this;
    }

    public function amoutnInEachSalesOrder()
    {
        return $this->records->sum(function($query){
            return  $query->unit_price * $query->qty;
        });
    }

    public function truncateSalesOrder()
    {
            foreach ($this->records as $record) {
                $record->product->update([
                    'stock' => $record->product->stock + $record->qty
                ]);
            }
            return $this->records()->forceDelete();
    }
}
