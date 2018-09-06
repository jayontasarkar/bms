@extends('layouts.backend.master')
@section('content')
	@component('layouts.backend.common.page-header')
		<strong>Outlet</strong>: {{ $outlet->name }} |  
		<small>
			{{ $outlet->address }}, {{ $outlet->thana->name }}, {{ $outlet->thana->district->name }}
		</small>
	@endcomponent
	<div class="row row-cards">
		<div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0">{{ number_format($total = $outlet->sales->sum('total_balance')) }}/=</div> 
	              	<div class="text-muted mb-4">Total Amount</div>
	            </div>
	        </div>
	    </div>
	    <div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0">{{ number_format($paid = $outlet->sales->sum('total_paid')) }}/=</div> 
	              	<div class="text-muted mb-4">Paid Amount</div>
	            </div>
	        </div>
	    </div>
	    <div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0">{{ number_format($discount = $outlet->sales->sum('total_discount')) }}/=</div> 
	              	<div class="text-muted mb-4">Total Discount</div>
	            </div>
	        </div>
	    </div>
	    <div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0 text-center">{{ number_format($total - $paid - $discount) }}/=</div> 
	              	<div class="text-muted mb-4 text-center">Due Amount</div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<a href="{{ route('outlets.show', [$outlet]) }}" class="btn btn-secondary" style="width: 30%;">Clear</a>
					<a href="{{ route('outlets.excel', array_merge($_GET, ['id' => $outlet->id])) }}" 
							style="width: 33%;" class="btn btn-primary"
					>Excel</a>
					<a href="{{ route('outlets.pdf', array_merge($_GET, ['id' => $outlet->id])) }}" style="width: 33%;" 
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
		                    	<a href="{{ route('outlets.show', [$outlet]) }}" class="btn btn-sm btn-secondary">
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
		                    	<a href="{{ route('outlets.show', [$outlet]) }}" class="btn btn-sm btn-secondary">
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
								Sales report of (monthly): 
								{{ config('bms.months.' . request('month')) . ', ' . request('year') }}
							</strong>
						@elseif(request()->has('from') && request()->has('to'))	
							<strong>
								Sales report of (dates): 
								{{ Carbon\Carbon::parse(request('from'))->format('d M, Y') }} - 
								{{ Carbon\Carbon::parse(request('to'))->format('d M, Y') }}
							</strong>
						@else
							<strong>All Sales reports</strong>	
						@endif
					</div> 
					@if(count($results = $outlet->sales->sortByDesc('created_at')))
						<div class="table-responsive">
							<table class="table card-table table-bordered table-vcenter text-nowrap" border="1">
								<thead>
									<tr class="bg-gray-dark">
										<th class="w-1">No.</th>
										<th>Memo No.</th>
										<th>Delivery Date</th>
										<th>Total Amount</th>
										<th>Total Paid</th>
										<th>Discount</th>
										<th>Total Due</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									@foreach($results as $key => $result)
										<tr>
											<td>
												<span class="text-muted">
													{{ $key + 1 }}
												</span>
											</td>
											<td>
												<outlet-memo 
														:sales="{{ json_encode($result) }}"
														:records="{{ json_encode($result->records) }}"
														:transactions="{{ json_encode($result->transactions) }}"
												></outlet-memo>
											</td>
											<td>
												{{ $result->sales_date->format('M d, Y') }}
											</td>
											<td>
												{{ number_format($result->total_balance) }}/= {!! $result->type ? '&nbsp;<span class="text-danger">(Opening)</span>' : '' !!}
											</td>
											<td>
												{{ number_format($result->total_paid) }}/=
											</td>
											<td>
												{{ number_format($result->total_discount) }}/=
											</td>
											<td>
												{{ number_format(
													$result->total_balance - 
													$result->total_paid - 
													$result->total_discount
												) }}/=
											</td>
											<td>
												<collection :sales="{{ json_encode($result) }}"
													     :url="'{{ route('sales.transactions.store', [$result]) }}'" 
												></collection>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<h3>No sales was found within the search criteria</h3>
					@endif
				</div>
			</div>
		</div>
	</div>
@stop