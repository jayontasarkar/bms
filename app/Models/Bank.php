<?php

namespace App\Models;

use App\Traits\Eloquent\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
	use SoftDeletes, Filterable;

    protected $fillable = [ 'name', 'branch', 'account_no' ];

    public function transactions()
    {
        return $this->morphMany('App\Models\Transaction', 'transactionable')->orderBy('transaction_date', 'desc');
    }
}
