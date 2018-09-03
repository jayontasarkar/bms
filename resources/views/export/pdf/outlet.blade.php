@extends('export.default')

@section('content')
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
	@if(count($results = $outletResults->sales->sortByDesc('created_at')))
		<table style="width: 100%;" border="1">
			<thead>
				<tr class="bg-gray-dark">
					<th class="w-1">No.</th>
					<th>Memo No.</th>
					<th>Delivery Date</th>
					<th>Total Amount</th>
					<th>Total Paid</th>
					<th>Discount</th>
					<th>Total Due</th>
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
							{{ $result->memo }}
						</td>
						<td>
							{{ $result->sales_date->format('M d, Y') }}
						</td>
						<td>
							{{ number_format($result->total_balance) }}/=
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
					</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<h3>No sales was found within the search criteria</h3>
	@endif
@stop