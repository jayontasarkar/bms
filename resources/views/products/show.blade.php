@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		<span class="text-secondary">{{ $product->code ? $product->code . ' - ' : '' }} 
		{{ $product->title }}</span>
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
					<a href="{{ route('products.show', [$product]) }}" class="btn btn-sm btn-block btn-secondary">
                		<i class="fe fe-x-circle"></i> Clear
                	</a> 
                	<hr>
					<div class="form-group">
	                    <label class="form-label"><strong>Search by date(s)</strong></label>
	                    <form id="search">
							<div class="form-group">
						    	<label for="from">From Date</label>
						        <input type="date" name="from" value="{{ request('from') }}" class="form-control" placeholder="From Date">
						    </div>
						    <div class="form-group">
						    	<label for="to">To Date</label>
						        <input type="date" name="to" value="{{ request('to') }}" class="form-control" placeholder="To Date">
						    </div>
						    <div class="form-group text-center">
						      <a href="{{ route('products.show', [$product]) }}" class="btn btn-gray mr-2">CLEAR</a>
						      <button type="submit" class="btn btn-info"><i class="fe fe-search"></i> Search</button>
						    </div>
						</form>
	                </div>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-body">
					@if(count($results))
						<div class="alert alert-success">
							@php 
								$title = "Product Transactions "; 
								if(request()->has('from') && request()->has('to')) {
									$title .= ' (' . Carbon\Carbon::parse(request('from'))->format('d M, Y') . ' - ' . 
									Carbon\Carbon::parse(request('to'))->format('d M, Y') . ')';
								}
								else{
									$title .= ' (All)';
								}
							@endphp
							<strong>{{ $title }}</strong>	
						</div>
						<div class="table-responsive">
							<table class="table card-table datatable table-bordered table-vcenter text-nowrap" border="1">
								<thead>
									<tr class="bg-gray-dark">
										<th>PO/SO</th>
										<th>Transaction Date</th>
										<th>Rate/Unit</th>
										<th>Quantity</th>
										<th>Total Amount</th>
										<th>Transaction Type</th>
									</tr>
								</thead>
								<tbody>
									@foreach($results as $result)
										<tr>
											<td>{{ $result->recordable->memo }}</td>
											<td>
												@if($result->recordable_type == 'App\Models\Purchase')
													{{ $result->recordable->purchase_date->format('M d, Y') }}
												@elseif($result->recordable_type == 'App\Models\Sales')	
													{{ $result->recordable->sales_date->format('M d, Y') }}
												@elseif($result->recordable_type == 'App\Models\ReadySale')
													{{ $result->recordable->ready_sale_date->format('M d, Y') }}
												@else
												
												@endif		
											</td>
											<td>
												{{ number_format($result->unit_price) }}/=
											</td>
											<td>
												{{ $result->qty }} {{ strtoupper($result->unit) }}
											</td>
											<td>
												{{ number_format($result->unit_price * $result->qty) }}/=
											</td>
											<td>
												<span class="badge badge-default">
													{{ str_replace("App\Models\\", '', $result->recordable_type) }}
												</span>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<div class="alert alert-danger text-center" role="alert">
							<strong>No product transaction in the search criteria</strong>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@stop

@if(count($results))
	@include('layouts.backend.common.datatable', [
		'title' => $title,
		'columns' => '[ 0, 1, 2, 3, 4, 5 ]'
	])
@endif

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