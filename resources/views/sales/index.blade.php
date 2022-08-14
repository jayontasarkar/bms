@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Outlet Sales by Sales Order
	@endcomponent
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					@include('layouts.backend.common._sidebarSearch', [
						'route' => route('sales.index')
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
								$title = "Sales reports "; 
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
					@if(count($sales))
		              <div class="table-responsive">
		                <table class="table card-table table-bordered table-vcenter text-nowrap datatable">
		                  <thead class="bg-gray-dark">
		                    <tr>
		                      <th>SO. No.</th>
		                      <th>Outlet/Clients</th>
		                      <th>Sales Date</th>
		                      <th>Total Amount</th>
		                      <th>Discount</th>
		                      <th></th>
		                    </tr>
		                  </thead>
		                  <tbody>
		                  	@php $grandTotal = 0; @endphp
		                    @foreach($sales as $sale)
		                      <tr>
		                        <td class="search">
		                        	<a href="{{ route('sales.show', [$sale]) }}">{{ $sale->memo }}</a>
		                        </td>
		                        <td class="search">
		                          <a href="{{ route('sales.show', [$sale->vendor]) }}" class="text-inherit">
		                            {{ optional($sale->outlet)->name }}
		                          </a>
		                        </td>
		                        <td>
		                          {{ $sale->sales_date->format('M d, Y') }}
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
		                          {{ number_format($sale->total_discount) }}/=
		                        </td>
		                        <td>
		                        	<outlet-memo 
											:sales="{{ json_encode($sale) }}"
											:records="{{ json_encode($sale->records) }}"
											:title="'Show'"
									></outlet-memo>	
		                        </td>
		                      </tr>
		                    @endforeach
		                    <tfoot class="bg-light">
		                    	<tr>
			                    	<td></td>
			                    	<td></td>
			                    	<td><strong>Total Amount:</strong></td>
			                    	<td><strong>{{ number_format($grandTotal) }}/=</strong></td>
			                    	<td>{{ number_format($sales->sum('total_discount')) }}/=</td>
			                    	<td></td>
			                    </tr>
		                    </tfoot>
		                  </tbody>
		                </table>
		              </div>
		            @else
		              <div class="alert alert-warning text-center">
		                <strong>No Sales was found with the search criteria</strong>
		              </div>
		            @endif
				</div>
			</div>
		</div>
	</div>
@stop


@include('layouts.backend.common.datatable', [
	'title' => $title,
	'columns' => '[ 0, 1, 2, 3, 4 ]',
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
