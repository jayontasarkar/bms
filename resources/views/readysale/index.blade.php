@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Store Ready Sales
		@slot('rightContent')
			<a href="#" onClick="history.go(-1); return false;" class="btn btn-sm ml-auto btn-gray">
				<i class="fe fe-corner-down-left mr-1"></i> Back
			</a>
		@endslot
	@endcomponent
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					@include('layouts.backend.common._sidebarSearch', [
						'route' => route('readysales.index')
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
								$title = "Ready Sale reports "; 
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
								   placeholder="Search by Sales Order">
						</div>
					</div>
					@if(count($readysales))
		              <div class="table-responsive">
		                <table class="table card-table table-bordered table-vcenter text-nowrap datatable">
		                  <thead class="bg-gray-dark">
		                    <tr>
		                      <th>Memo</th>
		                      <th>Customer Details</th>
		                      <th>Ready Sale Date</th>
		                      <th>Total Amount</th>
		                      <th>Total Paid</th>
		                      <th></th>
		                    </tr>
		                  </thead>
		                  <tbody>
		                  	@php $grandTotal = 0; $totalPaid = 0; @endphp
		                    @foreach($readysales as $index => $sale)
		                      <tr>
		                      	<td class="search">
		                        	<a href="{{ route('readysales.edit', [$sale]) }}">{{ $sale->memo }}</a>
		                        </td>
		                        <td class="search">
		                          {{ $sale->ready_sale_details }}
		                        </td>
		                        <td>
		                          {{ $sale->ready_sale_date->format('M d, Y') }}
		                        </td>
		                        <td>
		                        	@php
		                        	$total = $sale->records->sum(function($query){ 
		                        		return $query->unit_price * $query->qty; 
		                        	});
		                        	$grandTotal += $total;
		                        	@endphp
		                          {{ number_format($total) }}/= 
		                        </td>
		                        <td>
		                          @php
		                          $paid = $sale->transactions->sum('amount');
		                          $totalPaid += $paid;
		                          @endphp
		                          {{ number_format($paid) }}/=
		                        </td>
		                        <td>
		                        	<ready-sale-memo 
		                                :sales="{{ json_encode($sale) }}"
		                                :records="{{ json_encode($sale->records) }}"
		                                :title="'Show'"
		                            ></ready-sale-memo>
		                        </td>
		                      </tr>
		                    @endforeach
		                    <tfoot class="bg-light">
		                    	<tr>
		                    		<td></td>
		                    		<td></td>
		                    		<td>Total Amount:</td>
		                    		<td>{{ number_format($grandTotal) }}/=</td>
		                    		<td>{{ number_format($totalPaid) }}/=</td>
		                    		<td></td>
		                    	</tr>
		                    </tfoot>
		                  </tbody>
		                </table>
		              </div>
		            @else
		              <div class="alert alert-warning text-center">
		                <strong>No ready sale item was found with the criteria.</strong>
		              </div>
		            @endif
				</div>
			</div>
		</div>
	</div>
@stop


@include('layouts.backend.common.datatable', [
	'title' => $title,
	'columns' => '[0, 1, 2, 3, 4]',
	'searchCol' => 0
])

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
    		$("#search").submit(function() {
		      $(this).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
		      $(this).find("select").filter(function(){ return !this.value; }).attr("disabled", "disabled");
		      return true;
		    });
		});
	</script>
@endpush