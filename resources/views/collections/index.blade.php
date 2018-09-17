@extends('layouts.backend.master')
@section('content')
	@component('layouts.backend.common.page-header')
		<strong>Collections of Outlet</strong>: {{ $outlet->name }} |  
		<small>
			({{ $outlet->address }}, {{ $outlet->thana->name }})
		</small>
		@slot('rightContent')
			<a href="#" onClick="history.go(-1); return false;" class="btn btn-sm btn-gray ml-auto mt-4">
				<i class="fe fe-corner-down-left mr-1"></i> Back
			</a>
		@endslot
	@endcomponent
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<label class="form-label"><strong>Search by vendor & date(s)</strong></label>
					@include('layouts.backend.common._sidebarSearch', [
						'route' => route('outlet.collections.index', [$outlet])
					])
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-body">
					<div class="alert alert-success">
						<div class="row">
							<div class="col-md-12 pt-2">
								@php 
									$title = "Collection report of " . $outlet->name; 
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
						</div>
					</div> 
					@if(count($collections))
						<div class="table-responsive">
							<table class="table card-table table-bordered datatable table-vcenter text-nowrap" border="1">
								<thead>
									<tr class="bg-gray-dark">
										<th>Collection Date</th>
										<th>Vendor Name</th>
										<th>Collection Amount</th>
										<th>Entry Date</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@foreach($collections as $collection)
										<tr>
											<td>
						                        {{ $collection->transaction_date->format('M d, Y') }}
											</td>
											<td>
												{{ $collection->vendor->name }}
											</td>
											<td>
												{{ number_format($collection->amount) }}/=
											</td>
											<td>
						                        {{ $collection->created_at->format('M d, Y h:iA') }}
											</td>
											<td>
												<a href="{{ route('collections.edit', [$collection]) }}" class="btn btn-xs btn-info">
													<i class="fa fa-edit mr-1"></i> Edit
												</a>
											</td>
										</tr>
									@endforeach
									<tfoot class="bg-light">
										<tr>
											<td></td>
											<td><strong>Total Amount:</strong></td>
											<td>
												<strong>{{ number_format($collections->sum('amount')) }}/=</strong>
											</td>
											<td></td>
											<td></td>
										</tr>
									</tfoot>
								</tbody>
							</table>
						</div>
					@else
						<h3 class="text-center">No collection was found within the search criteria</h3>
					@endif
				</div>
			</div>
		</div>
	</div>
@stop

@include('layouts.backend.common.datatable', [
	'title' => $title,
	'columns' => '[ 0, 1, 2, 3 ]'
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