@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		<span class="text-secondary">{{ $product->code ? $product->code . ' - ' : '' }} 
		{{ $product->title }}</span> | Products Management
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
	                    <form>
	                    	<div class="form-group">
								<label class="selectgroup-item">From Date</label>
								<input type="date" name="from" required value="{{ request('from', date('Y-m-d')) }}" class="form-control">
		                    </div>
		                    <div class="form-group">
								<label class="selectgroup-item">To Date</label>
								<input type="date" name="to" required value="{{ request('to', date('Y-m-d')) }}" class="form-control">
		                    </div>
		                    <div class="form-group text-center">
		                    	<a href="{{ route('products.show', [$product]) }}" class="btn btn-sm btn-secondary">
		                    		<i class="fe fe-x-circle"></i> Clear
		                    	</a> 
		                    	<button type="submit" class="btn btn-sm btn-info">
		                    		<i class="fe fe-search"></i> Search
		                    	</button>
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
							@if(request()->has('from') && request()->has('to'))
								<strong>Product Transaction between {{ request('from') }} - {{ request('to') }}</strong>
							@else	
								<strong>
									All Product Transactions
								</strong>
							@endif	
						</div>
						<div class="table-responsive">
							<table class="table card-table table-bordered table-vcenter text-nowrap" border="1">
								<thead>
									<tr class="bg-gray-dark">
										<th>PO/SO</th>
										<th>Transaction Date</th>
										<th>Rate/Unit</th>
										<th>Quantity</th>
										<th>Transaction Type</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@foreach($results as $result)
										@if($result instanceof App\Models\PurchaseRecords)
											<tr>
												<td>{{ $result->purchase->memo }}</td>
												<td>
													{{ $result->purchase->purchase_date->format('M d, Y') }}
												</td>
												<td>
													{{ number_format($result->unit_price) }}/=
												</td>
												<td>
													{{ $result->qty }} {{ strtoupper($result->unit) }}
												</td>
												<td><span class="badge badge-info">Purchase from vendor</span></td>
												<td>{{ $result->purchase->vendor->name }}</td>
											</tr>
										@else
											<tr>
												<td>{{ $result->sale->memo }}</td>
												<td>
													{{ $result->sale->sales_date->format('M d, Y') }}
												</td>
												<td>
													{{ number_format($result->unit_price) }}/=
												</td>
												<td>
													{{ $result->qty }} {{ strtoupper($result->unit) }}
												</td>
												<td>
													<span class="badge badge-primary">Sales to outlet</span>
												</td>
												<td>{{ $result->sale->outlet->name }}</td>
											</tr>
										@endif
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

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.select-days').click(function(e){
				e.preventDefault();
				let noOfDays = $(this).children('input[name=days]').val();
				window.location.href="{{ route('products.show', [$product]) }}?days=" + noOfDays;
			});
			$('.select-months').click(function(e){
				e.preventDefault();
				let noOfMonths = $(this).children('input[name=months]').val();
				window.location.href="{{ route('products.show', [$product]) }}?months=" + noOfMonths;
			});
		});
	</script>
@endpush