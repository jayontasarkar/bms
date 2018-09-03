<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'transactionable_id', 'transactionable_type', 'comment', 'amount', 'transaction_date'
    ];

    protected $dates = ['transaction_date'];

    public function setTransactionDateAttribute($value)
    {
    	$this->attributes['transaction_date'] = Carbon::parse($value);
    }

    public function transactionable()
    {
        return $this->morphTo();
    }
}
