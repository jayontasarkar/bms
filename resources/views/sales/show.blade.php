@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Sales Order# {{ $sales->memo }}
		<span class="ml-3 mr-3">|</span>
		{{ $sales->sales_date->format('M d, Y') }}
	@slot('rightContent')
		<a href="#" onClick="history.go(-1); return false;" class="btn btn-sm btn-gray ml-auto mt-4">
			<i class="fe fe-corner-down-left mr-1"></i> Back
		</a>
	@endslot
@endcomponent
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header text-center">
				<strong>{{ $sales->outlet->name }}</strong>
			</div>
			<table class="table card-table">
				<tbody>
					<tr>
						<td>Proprietor</td>
						<td class="text-right"><span class="text-muted">{{ $sales->outlet->proprietor }}</span></td>
					</tr>
					<tr>
						<td>Phone/Mobile</td>
						<td class="text-right"><span class="text-muted">{{ $sales->outlet->phone }}</span></td>
					</tr>
					<tr>
						<td>Address</td>
						<td class="text-right"><span class="text-muted">{{ $sales->outlet->address }}</span></td>
					</tr>
					<tr>
						<td>Thana</td>
						<td class="text-right"><span class="text-muted">{{ $sales->outlet->thana->name }}</span></td>
					</tr>
					<tr>
						<td>District</td>
						<td class="text-right"><span class="text-muted">{{ $sales->outlet->thana->district->name }}</span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">
				@if($type = $sales->type)
					Opening Balance Report (created at {{ $sales->created_at->format('M d, Y h:i A') }})
				@else
					Sales Report (created at {{ $sales->created_at->format('M d, Y h:i A') }})
				@endif	
			</div>
			<div class="card-body">
				@if($type)
					<update-opening-balance
							:info="{{ json_encode($sales) }}"
							:url="'{{ route('outlets.opening-balance.update', [$sales]) }}'"
							:type="sales"
					></update-opening-balance>
				@else
					<update-sales :info="{{ json_encode($sales->records) }}"
						          :url="'{{ route('sales.update', [$sales]) }}'"
						          :discount="'{{ $sales->total_discount }}'"
						          :sales-date="'{{ $sales->sales_date }}'"
						          :products="{{ json_encode($products) }}"
					></update-sales>
				@endif	
			</div>
		</div>
		@if(count($sales->transactions))
			<div class="card">
				<div class="card-header">
					Transaction Report
				</div>
				<div class="card-body">
					<update-transactions
							:info="{{ json_encode($sales->transactions) }}"
							:url="'{{ route('sales.transactions.update', [$sales]) }}'"
					></update-transactions>
				</div>
			</div>
		@endif
	</div>
</div>
@stop