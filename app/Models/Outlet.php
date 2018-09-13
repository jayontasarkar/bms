<?php

namespace App\Models;

use App\Traits\Eloquent\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
	use Filterable, SoftDeletes;
	
    protected $fillable = [
    	'name', 'proprietor', 'phone', 'address', 'thana_id'
    ];

    public function thana()
    {
    	return $this->belongsTo(Thana::class);
    }

    public function sales()
    {
    	return $this->hasMany(Sales::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function openingBalances()
    {
        return $this->transactions->load('vendor')->where('type', true);
    }

    public function collections()
    {
        return $this->transactions->load('vendor')->where('type', false);
    }
}
