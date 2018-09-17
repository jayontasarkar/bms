@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Outlet# {{ $transaction->transactionable->name }}
		<span class="ml-3 mr-3">|</span>
		{{ $transaction->transaction_date->format('M d, Y') }}
	@slot('rightContent')
		<a href="{{ route('outlet.collections.index', [$transaction->transactionable]) }}" class="btn btn-sm btn-gray ml-auto mt-4">
			<i class="fe fe-corner-down-left mr-1"></i> Back
		</a>
	@endslot
@endcomponent
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header text-center">
				<strong><a href="{{ route('outlets.show', [$transaction->transactionable]) }}">{{ $transaction->transactionable->name }}</a></strong>
			</div>
			<table class="table card-table">
				<tbody>
					<tr>
						<td>Proprietor</td>
						<td class="text-right"><span class="text-muted">{{ $transaction->transactionable->proprietor }}</span></td>
					</tr>
					<tr>
						<td>Phone/Mobile</td>
						<td class="text-right"><span class="text-muted">{{ $transaction->transactionable->phone }}</span></td>
					</tr>
					<tr>
						<td>Address</td>
						<td class="text-right"><span class="text-muted">{{ $transaction->transactionable->address }}</span></td>
					</tr>
					<tr>
						<td>Thana</td>
						<td class="text-right"><span class="text-muted">{{ $transaction->transactionable->thana->name }}</span></td>
					</tr>
					<tr>
						<td>District</td>
						<td class="text-right"><span class="text-muted">{{ $transaction->transactionable->thana->district->name }}</span></td>
					</tr>
					<tr>
						<td colspan="2">
							<remove-btn :url="'{{ route('collections.destroy', [$transaction]) }}'"
							            :class-name="'btn-block btn-lg'"
							            :btn-text="'Remove This Collection?'"
							            :redirect-path="'{{ route('outlet.collections.index', [$transaction->transactionable]) }}'"
							></remove-btn>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">
				Update Collection (created at {{ $transaction->created_at->format('M d, Y h:i A') }})
			</div>
			<div class="card-body">
				<update-collection :districts="{{ json_encode($districts) }}"
                        :vendors="{{ json_encode($vendors) }}"
                        :url="'{{ route('collections.update', [$transaction]) }}'"
                        :transaction="{{ json_encode($transaction) }}"
                ></update-collection>
			</div>
		</div>
	</div>
</div>
@stop