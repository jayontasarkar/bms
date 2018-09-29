@extends('layouts.backend.master')
@section('content')
	@component('layouts.backend.common.page-header')
		<strong>Payment Report of {{ $vendor->name }}</strong>
		@slot('rightContent')
			<a href="{{ route('vendors.show', [$vendor]) }}" class="btn btn-sm btn-gray ml-auto mt-4">
				<i class="fe fe-corner-down-left mr-1"></i> Back
			</a>
		@endslot
	@endcomponent
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-header">
					<strong>Search by date(s)</strong>
				</div>
				<div class="card-body">
					<form action="" method="GET">
						<div class="form-group">
							<label for="from">From Date</label>
							<input type="date" class="form-control" name="from" value="{{ old('from') }}">
						</div>
						<div class="form-group mt-2">
							<label for="to">To Date</label>
							<input type="date" class="form-control" name="to" value="{{ old('to') }}">
						</div>
						<div class="form-group mt-2 text-right">
							<a href="{{ route('vendor.payments.index', [$vendor]) }}" class="btn btn-sm mr-2 btn-gray">
								<i class="fa fa-close"></i> Clear
							</a>
							<button type="submit" class="btn btn-sm btn-default">
								<i class="fa fa-search"></i> Search
							</button>
						</div>
					</form>	
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
									$title = "Payment report of " . $vendor->name; 
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
						</div>
					</div> 
					@if(count($payments))
						<div class="table-responsive">
							<table class="table card-table table-bordered datatable table-vcenter text-nowrap" border="1">
								<thead>
									<tr class="bg-gray-dark">
										<th>Payment Note</th>
										<th>Payment Date</th>
										<th>Amount</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@foreach($payments as $payment)
										<tr>
											<td>
						                        {{ $payment->comment ? : 'PAYMENT ON PURCHASE ORDER' }}
											</td>
											<td>
												{{ $payment->transaction_date->format('M d, Y') }}
											</td>
											<td>
												{{ number_format($payment->amount) }}/=
											</td>
											<td>
												<ul class="list-inline">
													<li class="list-inline-item">
														<update-payment 
																:url="'{{ route('vendor.payments.update', [$payment]) }}'"
																:transaction="{{ json_encode($payment) }}"
														></update-payment>
													</li>
													<li class="list-inline-item">
														<remove-btn 
														        :url="'{{ route('vendor.payments.destroy', [$payment]) }}'" 
														        :btn-text="'Remove'"
														></remove-btn>
													</li>
												</ul>
											</td>
										</tr>
									@endforeach
									<tfoot class="bg-light">
										<tr>
											<td></td>
											<td><strong>Total Amount:</strong></td>
											<td>
												<strong>{{ number_format($payments->sum('amount')) }}/=</strong>
											</td>
											<td></td>
										</tr>
									</tfoot>
								</tbody>
							</table>
						</div>
					@else
						<h3 class="text-center">No payment was found within the search criteria</h3>
					@endif
				</div>
			</div>
		</div>
	</div>
@stop

@include('layouts.backend.common.datatable', [
	'title' => $title,
	'columns' => '[ 0, 1, 2 ]'
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