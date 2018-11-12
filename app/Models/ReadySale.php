<?php

namespace App\Models;

use App\Traits\Eloquent\Filterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReadySale extends Model
{
	use SoftDeletes, Filterable;

    protected $fillable = [
    	'memo', 'vendor_id', 'ready_sale_details', 'total_discount', 'ready_sale_date'
    ];

    protected $dates = ['ready_sale_date'];

    public function setReadySaleDateAttribute($value)
    {
    	$this->attributes['ready_sale_date'] = Carbon::parse($value);
    }

    public function records()
    {
    	return $this->morphMany(Record::class, 'recordable');
    }

    public function transactions()
    {
    	return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function insertRelationalData($request)
    {
    	$this->transactions()->create(array_merge(
    		$request->only('amount', 'vendor_id'), ['transaction_date' => $request->ready_sale_date]
    	));
    	$sales = $this->records()->createMany($request->only('records')['records']);
    	foreach($sales as $sale) {
    		$sale->product->update([
                'stock' => $sale->product->stock - $sale->qty
            ]);
    	}
    	return $this;
    }
}
