<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VendorExport implements FromView, ShouldAutoSize
{
	protected $purchases;

	public function __construct($purchases)
	{
		$this->purchases = $purchases;
	}

    public function view(): View
    {
        return view('export.excel.vendor', [
            'purchases' => $this->purchases
        ]);
    }
}
