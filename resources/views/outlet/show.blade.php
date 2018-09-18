@extends('layouts.backend.master')
@section('content')
	@component('layouts.backend.common.page-header')
		<strong>Outlet</strong>: {{ $outlet->name }} |  
		<small>
			{{ $outlet->address }}, {{ $outlet->thana->name }}, {{ $outlet->thana->district->name }}
		</small>
		@slot('rightContent')
			<a href="{{ route('outlets.edit', [$outlet]) }}" class="btn btn-sm btn-info ml-auto mr-3 mt-4">
				<i class="fa fa-edit mr-1"></i> Edit
			</a>
			<a href="#" onClick="history.go(-1); return false;" class="btn btn-sm btn-gray mt-4">
				<i class="fe fe-corner-down-left mr-1"></i> Back
			</a>
		@endslot
	@endcomponent
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					@include('outlet.views._overall_balance_report_modal')
					@if(count($overall['openingResults']))
						@include('outlet.views._opening_balance_report_modal')
					@endif
					<a href="{{ route('outlet.collections.index', [$outlet]) }}" class="btn btn-block btn-gray">Collection Report</a>
					<hr>
                    <label class="form-label"><strong>Search by vendor & date(s)</strong></label>
                    @include('layouts.backend.common._sidebarSearch', [
						'route' => route('outlets.show', [$outlet])
					])
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<sale-outlet-product :vendors="{{ json_encode($vendors) }}"
			                        :outlet="{{ json_encode($outlet) }}"
			                        :url="'{{ route('sales.store') }}'"
			                        :class-name="'d-block width--100'"
			                        :btn-class="'btn-block'"
			                ></sale-outlet-product>
						</div>
						<div class="col-md-4">
							<collection-from-outlet
									:url="'{{ route('outlets.opening-balance.store', [$outlet]) }}'"
									:vendors="{{ json_encode($vendors) }}"
							></collection-from-outlet>
						</div>
						<div class="col-md-4">
							<add-opening-balance
									:url="'{{ route('outlets.opening-balance.store', [$outlet]) }}'"
									:vendors="{{ json_encode($vendors) }}"
							>
							</add-opening-balance>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="alert alert-success">
						<div class="row">
							<div class="col-md-8 pt-2">
								@php 
									$title = "Sales report of " . $outlet->name; 
									if(request()->has('vendor')) {
										$title .= ' for ' . App\Models\Vendor::find(request('vendor'))->name;
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
							<div class="col-md-4">
								<input type="text" class="form-control" placeholder="Search by Sales Order" id="filter-table">
							</div>
						</div>
					</div> 
					@if(count($results = $outlet->sales))
						<div class="table-responsive">
							<table class="table card-table table-bordered datatable table-vcenter text-nowrap" border="1">
								<thead>
									<tr class="bg-gray-dark">
										<th>Memo</th>
										<th>Sales Date</th>
										<th>Vendor Name</th>
										<th>Total Amount</th>
										<th>Discount</th>
									</tr>
								</thead>
								<tbody>
									@php $total = 0; @endphp
									@foreach($results as $key => $result)
										<tr>
											<td>
						                        <a href="{{ route('sales.show', [$result]) }}">{{ $result->memo }}</a>
											</td>
											<td>
												{{ $result->sales_date->format('M d, Y') }}
											</td>
											<td>
												{{ $result->vendor->name }}
											</td>
											<td>
												@php 
													$sum = $result->amoutnInEachSalesOrder();
													$total += $sum;
												@endphp
												{{ number_format($sum) }}/=
											</td>
											<td>
												{{ number_format($result->total_discount) }}/=
											</td>
										</tr>
									@endforeach
									<tfoot class="bg-light">
										<tr>
											<td></td>
											<td></td>
											<td><strong>Total Amount:</strong></td>
											<td>
												<strong>{{ number_format($total) }}/=</strong>
											</td>
											<td></td>
										</tr>
									</tfoot>
								</tbody>
							</table>
						</div>
					@else
						<h3 class="text-center">No sales was found within the search criteria</h3>
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