@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Sales Order# {{ $sales->memo }}
		<span class="ml-3 mr-3">|</span>
		Outlet: {{ $sales->outlet->name }}
		<span class="ml-3 mr-3">|</span>
		{{ $sales->sales_date->format('M d, Y') }}
	@slot('rightContent')
		<a href="{{ route('outlets.show', [$sales->outlet, 'vendor' => $sales->vendor_id]) }}" class="btn btn-sm btn-gray ml-auto mt-4">
			<i class="fe fe-corner-down-left mr-1"></i> Back
		</a>
	@endslot
@endcomponent
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header text-center">
				<strong><a href="{{ route('outlets.show', [$sales->outlet]) }}">{{ $sales->outlet->name }}</a></strong>
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
				Sales Report (created at {{ $sales->created_at->format('M d, Y h:i A') }})
			</div>
			<div class="card-body">
				<update-sales :info="{{ json_encode($sales) }}"
					          :url="'{{ route('sales.update', [$sales]) }}'"
					          :discount="'{{ $sales->total_discount }}'"
					          :products="{{ json_encode($products) }}"
				></update-sales>
			</div>
		</div>
	</div>
</div>
@stop