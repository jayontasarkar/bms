<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OutletExport implements FromView, ShouldAutoSize
{
	protected $sales;

	public function __construct($sales)
	{
		$this->sales = $sales;
	}

    public function view(): View
    {
        return view('export.excel.outlet', [
            'sales' => $this->sales
        ]);
    }
}
