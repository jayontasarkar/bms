<?php

namespace App\Http\ViewComposers;

use App\Http\Filters\{SalesFilter, PurchaseFilter};
use App\Models\{Purchase, Sales};
use Illuminate\View\View;

class PendingPurchaseAndSalesComposer
{
	private $purchaseFilter;

	private $salesFilter;

	private $purchases;

	private $sales;

	public function __construct(SalesFilter $purchaseFilter, SalesFilter $salesFilter)
	{
		$this->purchaseFilter = $purchaseFilter;
		$this->salesFilter = $salesFilter;
	}

	public function compose(View $view)
	{
		if( ! $this->purchases ) {
			$this->purchases = Purchase::vendorsWithDuePayments($this->purchaseFilter);
		}
		if( !$this->sales) {
			$this->sales = Sales::outletsWithDuePayments($this->salesFilter);
		}

		$view->with('pendingPurchases', $this->purchases)
			 ->with('pendingSales', $this->sales);
	}
}