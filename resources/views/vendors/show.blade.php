@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		<span class="text-secondary">{{ $result->name }}</span> | Vendor Management
	@endcomponent
	<div class="row row-cards">
		<div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0">
						{{ number_format($opening = $result->openingBalances() ? $result->openingBalances()->amount : 0) }}/=
					</div> 
	              	<div class="text-muted mb-4">Opening Balances</div>
	            </div>
	        </div>
	    </div>
	    <div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					@php
						$total = $result->purchases->sum(function($query){ 
							return $query->total_balance - $query->total_discount;
						});
					@endphp
					<div class="h1 m-0">{{ number_format($total) }}/=</div> 
	              	<div class="text-muted mb-4">Purchase Amount</div>
	            </div>
	        </div>
	    </div>
	    <div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0">{{ number_format($paid = $result->payments()->sum('amount')) }}/=</div> 
	              	<div class="text-muted mb-4">Total Paid</div>
	            </div>
	        </div>
	    </div>
	    <div class="col-6 col-sm-3 col-lg-3">
			<div class="card">
				<div class="card-body p-3 text-center">
					<div class="h1 m-0 text-center">{{ number_format($opening + $total - $paid) }}/=</div> 
	              	<div class="text-muted mb-4 text-center">Amount to Pay</div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<div style="width: 48%; margin-right: 2%; float: left;">
						<create-payment :url="'{{ route('vendor.payments.store', [$result]) }}'"></create-payment>
					</div>
					<div style="width: 48%; float: left;">
						<a href="{{ route('vendor.payments.index', [$result]) }}" class="btn btn-gray btn-block">
							Show Report
						</a>
					</div>
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
						<div class="row">
							<div class="col-md-8 pt-3">
								@php 
								$title = ''; 
								if(request()->has('year') && request()->has('month')) {
									$title = "Purchase report of (monthly): " . 
									    config('bms.months.' . request('month')) . ', ' . request('year');
								}elseif(request()->has('from') && request()->has('to'))	{
									$title = "Purchase report of (dates): " . 
										Carbon\Carbon::parse(request('from'))->format('d M, Y') . " - " .
										Carbon\Carbon::parse(request('to'))->format('d M, Y');
								}else{
									$title = "All Purchase reports of <em>" . $result->name . "</em>";	
								}	
								@endphp
								<strong>{!! $title !!}</strong>
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control" placeholder="Search by Purchase Order" id="filter-table">
							</div>
						</div>
					</div> 
					@if(count($results = $result->purchases->sortByDesc('created_at')))
						<div class="table-responsive">
							<table class="table card-table table-bordered datatable table-vcenter text-nowrap" border="1">
								<thead>
									<tr class="bg-gray-dark">
										<th class="w-1">No.</th>
										<th>Memo No.</th>
										<th>Purchase Date</th>
										<th>Total Amount</th>
										<th>Discount</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@php
										$totalAmount = 0;
										$totalDiscount = 0;
									@endphp
									@foreach($results as $key => $purchase)
										@php
											$totalAmount += $purchase->total_balance;
											$totalDiscount += $purchase->total_discount;
										@endphp
										<tr>
											<td>
												<span class="text-muted">
													{{ $key + 1 }}
												</span>
											</td>
											<td>
												<a href="{{ route('purchases.show', [$purchase]) }}">{{ $purchase->memo }}</a>
											</td>
											<td>
												{{ $purchase->purchase_date->format('M d, Y') }}
											</td>
											<td>
												{{ number_format($purchase->total_balance) }}/= &nbsp;
											</td>
											<td>
												{{ number_format($purchase->total_discount) }}/=
											</td>
											<td>
												<vendor-memo 
														:purchase="{{ json_encode($purchase) }}"
														:title="'show'"
														:records="{{ json_encode($purchase->records) }}"
														:transactions="{{ json_encode($purchase->transactions) }}"
												></vendor-memo>
											</td>
										</tr>
									@endforeach
									<tfoot>
										<tr class="bg-gray text-light">
											<td></td>
											<td></td>
											<td>Total Amount:</td>
											<td>{{ number_format($totalAmount) }}/=</td>
											<td>{{ number_format($totalDiscount) }}/=</td>
											<td></td>
										</tr>
									</tfoot>
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

@include('layouts.backend.common.datatable', [
	'title' => $title,
	'columns' => '[ 1, 2, 3, 4]',
	'searchCol' => 1
])

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