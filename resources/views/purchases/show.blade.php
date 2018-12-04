@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Purchase Order# {{ $purchase->memo }} <span class="ml-3 mr-3">|</span> {{ $purchase->purchase_date->format('M d, Y') }}
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
					<strong>{{ $purchase->vendor->name }}</strong>
				</div>
				<table class="table card-table">
					<tbody>
						<tr>
							<td>Phone/Mobile</td>
							<td class="text-right"><span class="text-muted">{{ $purchase->vendor->phone }}</span></td>
						</tr>
						<tr>
							<td>Address</td>
							<td class="text-right"><span class="text-muted">{{ $purchase->vendor->address }}</span></td>
						</tr>
					</tbody>
				</table>
				<hr>
				<p class="p-1">
					<remove-btn
						:url="'{{ route('purchases.destroy', [$purchase]) }}'"
						:class-name="'btn-lg btn-block'"
						:title="'Are you sure to remove this purchase order?'"
						:btn-text="'Remove Purchase Order?'"
						:redirect-path="'{{ route('purchases.index') }}'"
					></remove-btn>
				</p>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					@if($type = $purchase->type)
						Opening Balance Report (created at {{ $purchase->created_at->format('M d, Y h:i A') }})
					@else
						Purchase Report (created at {{ $purchase->created_at->format('M d, Y h:i A') }})
					@endif
				</div>
				<div class="card-body">
					@if($type)
						<update-opening-balance
								:info="{{ json_encode($purchase) }}"
								:url="'{{ route('vendors.opening-balance.update', [$purchase]) }}'"
								:type="'purchase'"
						></update-opening-balance>
					@else
						<update-purchases :info="{{ json_encode($purchase->records) }}"
							          :url="'{{ route('purchases.update', [$purchase]) }}'"
							          :discount="'{{ $purchase->total_discount }}'"
							          :sales-date="'{{ $purchase->purchase_date }}'"
							          :products="{{ json_encode($products) }}"
							          :order-memo="'{{ $purchase->memo }}'"
						></update-purchases>
					@endif
				</div>
			</div>
			@if(count($purchase->transactions))
				<div class="card">
					<div class="card-header">
						Transaction Report
					</div>
					<div class="card-body">
						<update-transactions
								:info="{{ json_encode($purchase->transactions) }}"
								:url="'{{ route('purchases.transactions.update', [$purchase]) }}'"
						></update-transactions>
					</div>
				</div>
			@endif
		</div>
	</div>
@stop
{{--

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
@stop --}}