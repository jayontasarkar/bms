<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
    	'name', 'address', 'phone'
    ];

    public function purchases()
    {
    	return $this->hasMany(Purchase::class)->orderBy('created_at', 'desc');
    }

    public function products()
    {
    	return $this->hasMany(Product::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function openingBalances()
    {
        return $this->transactions->where('type', true)->first();
    }

    public function payments()
    {
        return $this->transactions->where('type', false);
    }

    public function overallVendorReport()
    {
        $totalToPay = 0;
        $title = 'Overall Financial Report';
        $openingResults = $this->transactions->where('type', true);
        $totalPaid = $this->transactions->where('type', false)->sum('amount');
        $opening   = $this->transactions->where('type', true)->sum('amount');
        if(count($records = $this->purchases)) {
            foreach($records as $record) {
                $totalToPay += $record->amoutnInEachPurchaseOrder();
            }
        }

        return [
            'openingResults' => $openingResults->load('vendor'),
            'opening' => $opening,
            'toPay'   => $totalToPay,
            'paid'    => $totalPaid,
            'title'   => $title
        ];
    }

    public function createOrUpdateOpeningBalance($request)
    {
        if(count($this->openingBalances()) > 0) {
            return $this->transactions->where('type', true)->first()->update([
                'amount' => $request->amount
            ]);
        }

        return $this->transactions()->create([
            'comment' => 'OPENING BALANCE',
            'amount'  => $request->has('amount') ? $request->amount : 0,
            'type'    => 1,
            'transaction_date' => now()
        ]);
    }
}
