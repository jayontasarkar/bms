<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\Eloquent\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes, Filterable;
    
    protected $fillable = [
    	'title', 
    	'amount',
    	'user_id',
        'vendor_id',
        'deleted_at',
    	'description',
    	'expense_date',
    ];

    protected $dates = ['deleted_at', 'expense_date'];

    /**
     * Expense Date Mutator
     * @param [type] $value [description]
     */
    public function setExpenseDateAttribute($value)
    {
        $this->attributes['expense_date'] = Carbon::parse($value);
    }

    /**
     * Expense entry belongs to an operator
     * 
     * @return [type] [description]
     */
    public function operator()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
