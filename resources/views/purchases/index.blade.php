@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Incomplete Payment Pruchases
	@endcomponent
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					@include('layouts.backend.common._sidebarSearch', [
						'route' => route('purchases.index')
					])
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-body">
					<div class="row mb-5 alert alert-success">
						<div class="col-md-9 pt-2">
							@php 
								$title = "Purchase reports "; 
								if(request()->has('vendor')) {
									$title .= ' of ' . App\Models\Vendor::find(request('vendor'))->name;
								}
								elseif(request()->has('from') && request()->has('to')) {
									$title .= ' (' . Carbon\Carbon::parse(request('from'))->format('d M, Y') . ' - ' . 
									Carbon\Carbon::parse(request('to'))->format('d M, Y') . ')';
								}
								else{
									$title .= ' (All)';
								}
							@endphp
							<strong>{{ $title }}</strong>
						</div>
						<div class="col-md-3">
							<input type="text" name="" id="filter-table" class="form-control" 
								   placeholder="Search by Purchase Order">
						</div>
					</div>
					@if(count($purchases))
		              <div class="table-responsive">
		                <table class="table card-table table-bordered table-vcenter text-nowrap datatable">
		                  <thead class="bg-gray-dark">
		                    <tr>
		                      <th class="w-1">PO. No.</th>
		                      <th>Purchase Date</th>
		                      <th>Total Amount</th>
		                      <th>Total Paid</th>
		                      <th>Discount</th>
		                      <th>Due Amount</th>
		                      <th></th>
		                    </tr>
		                  </thead>
		                  <tbody>
		                  	@php
		                  		$totalAmount = 0;
		                  		$totalPaid = 0;
		                  		$totalDiscount = 0;
		                  		$totalDue = 0;
		                  	@endphp
		                    @foreach($purchases as $purchase)
		                      <tr>
		                        <td class="search">
		                        	<a href="{{ route('purchases.show', [$purchase]) }}"> {{ $purchase->memo }}</a>
		                        </td>
		                        <td>
		                          {{ $purchase->purchase_date->format('M d, Y') }}
		                        </td>
		                        <td>
		                          {{ number_format($total = $purchase->total_balance) }}/= &nbsp;
		                          {{ $purchase->type ? '(Opening)' : '' }}
		                        </td>
		                        <td>
		                          {{ number_format($paid = $purchase->total_paid) }}/=
		                        </td>
		                        <td>
		                          {{ number_format($discount = $purchase->total_discount) }}/=
		                        </td>
		                        <td>
		                          {{ number_format($total - $paid - $discount) }}/=
		                        </td>
		                        @php
		                        	$totalAmount += $total;
			                  		$totalPaid += $paid;
			                  		$totalDiscount += $discount;
			                  		$totalDue += ($total - $paid - $discount);
		                        @endphp
		                        <td>
		                        	@if( !$purchase->type )
			                        	<vendor-memo 
												:purchase="{{ json_encode($purchase) }}"
												:records="{{ json_encode($purchase->records) }}"
												:transactions="{{ json_encode($purchase->transactions) }}"
												:title="'Show'"
										></vendor-memo>
									@else
			                            <span class="badge badge-danger"><strong>Opening Balance</strong></span>
			                        @endif 	
		                        </td>
		                      </tr>
		                    @endforeach
		                    <tr style="font-weight: bold;">
		                    	<td></td>
		                    	<td>Total Amount:</td>
		                    	<td>{{ number_format($totalAmount) }}/=</td>
		                    	<td>{{ number_format($totalPaid) }}/=</td>
		                    	<td>{{ number_format($totalDiscount) }}/=</td>
		                    	<td>{{ number_format($totalDue) }}/=</td>
		                    	<td></td>
		                    </tr>
		                  </tbody>
		                </table>
		              </div>
		            @else
		              <div class="alert alert-warning text-center">
		                <strong>No purchase was found with pending amount</strong>
		              </div>
		            @endif
				</div>
			</div>
		</div>
	</div>
@stop

@include('layouts.backend.common.datatable', [
	'title' => $title,
	'columns' => '[0, 1, 2, 3, 4, 5]',
	'searchCol' => 0
])


@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
	        $("#search").submit(function() {
		      $(this).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
		      return true;
		    });
		});
	</script>
@endpush