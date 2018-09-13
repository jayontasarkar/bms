<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReadySale extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
    	'memo', 'vendor_id', 'ready_sale_details', 'total_discount', 'sales_date'
    ];

    public function records()
    {
    	return $this->hasMany(SalesRecord::class);
    }
}
