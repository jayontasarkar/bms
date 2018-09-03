@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Store Management
		@slot('rightContent')
	        <purchase-product :vendors="{{ json_encode($vendors) }}" 
	        				  :products="{{ json_encode($products) }}"
	        				  :url="'{{ route('purchases.store') }}'"
	        ></purchase-product>
	        <sale-product :products="{{ json_encode($products) }}"
	        			  :districts="{{ json_encode($districts) }}"
	        			  :url="'{{ route('sales.store') }}'"
	        ></sale-product>
	    @endslot
	@endcomponent
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-header">
					<strong>Recent Purchases & Sales</strong>
				</div>
				<div class="card-body">
					@if(count($latestSalesAndPurhases))
						<table class="table card-table">
	                    	<tbody>
	                    		@foreach($latestSalesAndPurhases as $history)
									<tr>
		                      			<td>
		                      				@if($history instanceof App\Models\Purchase)
		                      					<a href="{{ route('purchases.show', [$history]) }}">{{ $history->memo }}</a>
		                      				@else
		                      					<a href="{{ route('sales.show', [$history]) }}">{{ $history->memo }}</a>
		                      				@endif	
		                      			</td>
		                      			<td>{{ number_format($history->total_balance) }}/=</td>
		                      			<td class="text-right">
		                      				@if($history instanceof App\Models\Purchase)
												<span class="badge badge-success">Purchase</span>
		                      				@else
												<span class="badge badge-danger">Sales</span>
		                      				@endif
		                      			</td>
		                    		</tr>
	                    		@endforeach
	                  		</tbody>
	              		</table>
					@else
						<h3 class="text-center">No Purchase/Sales history was found</h3>
					@endif
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-header">
					<div class="row" style="width: 100%;">
						<div class="col-md-7 pt-2">
							<strong style="font-size: 1.3em;">List of Products & Availability in Store</strong>
						</div>
						<div class="col-md-5">
							<select name="vendor" class="form-control" id="select-vendor" style="width: 100%;">
								<option value="">All Vendors</option>
								@forelse($vendors as $vendor)
									<option value="{{ $vendor->id }}" {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
										{{ $vendor->name }}
									</option>
								@empty

								@endforelse
							</select>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
							<table class="table card-table table-bordered table-vcenter text-nowrap" border="1">
								<thead>
									<tr class="bg-gray-dark">
										<th class="w-1">No.</th>
										<th>Code</th>
										<th>Product Title</th>
										<th>Vendor Name</th>
										<th>Quantity</th>
										<th>Unit</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
				</div>
			</div>
		</div>
	</div>
@stop

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#select-vendor').change(function(e){
				e.preventDefault();
				if($(this).val() != '') {
					window.location.href="{{ route('stores.index') }}?vendor=" + $(this).val();
				}else{
					window.location.href="{{ route('stores.index') }}";
				}
			});
		});
	</script>
@endpush