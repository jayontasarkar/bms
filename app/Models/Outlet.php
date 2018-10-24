<?php

namespace App\Models;

use App\Traits\Eloquent\Filterable;
use Carbon\Carbon;
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
        if(request()->has('vendor')) {
            return $this->transactions->where('vendor_id', request('vendor'))->load('vendor')->where('type', true);
        }
        return $this->transactions->load('vendor')->where('type', true); 
    }

    public function collections()
    {
         if(request()->has('vendor')) {
            return $this->transactions->where('vendor_id', request('vendor'))->load('vendor')->where('type', false);
        }
        return $this->transactions->load('vendor')->where('type', false);
    }

    public function overallCollectionReport()
    {
        $totalToPay = 0;
        $title = 'Overall Financial Report';
        $openingResults = $this->transactions->where('type', true);
        if(request()->has('vendor')) {
            $totalPaid = $this->transactions->where('type', false)->where('vendor_id', request('vendor'))->sum('amount');
            $opening   = $this->transactions->where('type', true)->where('vendor_id', request('vendor'))->sum('amount');
            if(count($this->sales)) {
                foreach($records = $this->sales->where('vendor_id', request('vendor')) as $record) {
                    $totalToPay += $record->amoutnInEachSalesOrder();
                    $totalToPay -=$record->total_discount;
                }
                if(count($records)) {
                    $title = 'Overall Financial Report for: ' . $records->first()->vendor->name;
                }else{
                    $title = 'Overall Financial Report for: ' . Vendor::find(request('vendor'))->name;
                }
            }
        }else{
            $totalPaid = $this->transactions->where('type', false)->sum('amount');
            $opening   = $this->transactions->where('type', true)->sum('amount');
            if(count($records = $this->sales)) {
                foreach($records as $record) {
                    $totalToPay += $record->amoutnInEachSalesOrder();
                    $totalToPay -=$record->total_discount;
                }
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

    public function totalSalesByVendor($vendor = false)
    {
        if($vendor) {
            $sales = $this->sales->where('vendor_id', $vendor);
        }else{
            $sales = $this->sales;
        }
        if(count($sales)) {
            $total = 0;
            foreach ($sales as $sale) {
                $sum = $sale->records->sum(function($query){ 
                    return  $query->unit_price * $query->qty; 
                });
                $total += ($sum - $sale->total_discount);
            }
            return $total;
        }
        return 0;
    }
}
