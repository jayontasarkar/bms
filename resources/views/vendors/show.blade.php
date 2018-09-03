@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		<span class="text-secondary">{{ $result->name }}</span> | Vendor Management
	@endcomponent
	<div class="row row-cards">
		<div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0">{{ number_format($total = $result->purchases->sum('total_balance')) }}/=</div> 
	              	<div class="text-muted mb-4">Total Amount</div>
	            </div>
	        </div>
	    </div>
	    <div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0">{{ number_format($paid = $result->purchases->sum('total_paid')) }}/=</div> 
	              	<div class="text-muted mb-4">Paid Amount</div>
	            </div>
	        </div>
	    </div>
	    <div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0">{{ number_format($discount = $result->purchases->sum('total_discount')) }}/=</div> 
	              	<div class="text-muted mb-4">Total Discount</div>
	            </div>
	        </div>
	    </div>
	    <div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0 text-center">{{ number_format($total - $paid - $discount) }}/=</div> 
	              	<div class="text-muted mb-4 text-center">Amount to Pay</div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<a href="{{ route('vendors.show', [$result]) }}" class="btn btn-secondary" style="width: 30%;">Clear</a>
					<a href="{{ route('vendors.excel', array_merge($_GET, ['id' => $result->id])) }}" 
							style="width: 33%;" class="btn btn-primary"
					>Excel</a>
					<a href="{{ route('vendors.pdf', array_merge($_GET, ['id' => $result->id])) }}" style="width: 33%;" 
						class="btn btn-danger"
					>PDF</a>
					<hr>
					<div class="form-group">
	                    <label class="form-label"><strong>Search by month</strong></label>
	                    <form>
	                    	<div class="form-group">
	                    		<div class="row">
	                    			<div class="col-md-6">
	                    				<label for="month">Month</label>
	                    				<select name="month" id="month" class="form-control">
	                    					@foreach(config('bms.months') as $key => $month)
	                    						<option value="{{ $key }}" {{ old('month', date('m')) == $key ? 'selected' : '' }}>
	                    							{{ $month }}
	                    						</option>
	                    					@endforeach
	                    				</select>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<label for="year">Year</label>
	                    				<select name="year" id="year" class="form-control">
	                    					@for($i = 2018; $i <= date('Y') + 5; $i++)
	                    						<option value="{{ $i }}" {{ old('year', date('Y')) == $i ? 'selected' : '' }}>
	                    							{{ $i }}
	                    						</option>
	                    					@endfor
	                    				</select>	
	                    			</div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group text-center">
		                    	<a href="{{ route('vendors.show', [$result]) }}" class="btn btn-sm btn-secondary">
		                    		<i class="fe fe-x-circle"></i> Clear
		                    	</a> 
		                    	<button type="submit" class="btn btn-sm btn-info">
		                    		<i class="fe fe-search"></i> Search
		                    	</button>
		                    </div>
	                    </form>
	                </div>
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
		                    	<a href="{{ route('vendors.show', [$result]) }}" class="btn btn-sm btn-secondary">
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
					<div class="alert alert-success">
						@if(request()->has('year') && request()->has('month'))
							<strong>
								Purchase report of (monthly): 
								{{ config('bms.months.' . request('month')) . ', ' . request('year') }}
							</strong>
						@elseif(request()->has('from') && request()->has('to'))	
							<strong>
								Purchase report of (dates): 
								{{ Carbon\Carbon::parse(request('from'))->format('d M, Y') }} - 
								{{ Carbon\Carbon::parse(request('to'))->format('d M, Y') }}
							</strong>
						@else
							<strong>All Purchase reports of <em>{{ $result->name }}</em></strong>	
						@endif
					</div> 
					@if(count($results = $result->purchases->sortByDesc('created_at')))
						<div class="table-responsive">
							<table class="table card-table table-bordered table-vcenter text-nowrap" border="1">
								<thead>
									<tr class="bg-gray-dark">
										<th class="w-1">No.</th>
										<th>Memo No.</th>
										<th>Purchase Date</th>
										<th>Total Amount</th>
										<th>Total Paid</th>
										<th>Discount</th>
										<th>Total Due</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									@foreach($results as $key => $purchase)
										<tr>
											<td>
												<span class="text-muted">
													{{ $key + 1 }}
												</span>
											</td>
											<td>
												<vendor-memo 
														:purchase="{{ json_encode($purchase) }}"
														:records="{{ json_encode($purchase->records) }}"
														:transactions="{{ json_encode($purchase->transactions) }}"
												></vendor-memo>
											</td>
											<td>
												{{ $purchase->purchase_date->format('M d, Y') }}
											</td>
											<td>
												{{ number_format($purchase->total_balance) }}/=
											</td>
											<td>
												{{ number_format($purchase->total_paid) }}/=
											</td>
											<td>
												{{ number_format($purchase->total_discount) }}/=
											</td>
											<td>
												{{ number_format(
													$purchase->total_balance - 
													$purchase->total_paid - 
													$purchase->total_discount
												) }}/=
											</td>
											<td>
												<payment :purchase="{{ json_encode($purchase->load('vendor')) }}"
													     :url="'{{ route('purchases.transactions.store', [$purchase]) }}'" 
												></payment>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<h3>No purchase was found within the search criteria</h3>
					@endif
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
					window.location.href="{{ route('products.index') }}?vendor=" + $(this).val();
				}
			});
		});
	</script>
@endpush