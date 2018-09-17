@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Ready Sale Order# {{ $readySale->memo }}
		<span class="ml-3 mr-3">|</span>
		{{ $readySale->ready_sale_date->format('M d, Y') }}
	@slot('rightContent')
		<a href="{{ route('readysales.index') }}" class="btn btn-sm btn-gray ml-auto mt-4">
			<i class="fe fe-corner-down-left mr-1"></i> Back
		</a>
	@endslot
@endcomponent
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header text-center">
				<strong><a href="{{ route('readysales.index') }}">{{ $readySale->ready_sale_details }}</a></strong>
			</div>
			<table class="table card-table">
				<tbody>
					<tr>
						<td colspan="2">
							<remove-btn :url="'{{ route('readysales.destroy', [$readySale]) }}'"
							            :class-name="'btn-block btn-lg'"
							            :btn-text="'Remove Ready Sale?'"
							            :redirect-path="'{{ route('readysales.index') }}'"
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
				Ready Sale Report (created at {{ $readySale->created_at->format('M d, Y h:i A') }})
			</div>
			<div class="card-body">
				<update-ready-sales :info="{{ json_encode($readySale) }}"
					          :url="'{{ route('readysales.update', [$readySale]) }}'"
					          :products="{{ json_encode($products) }}"
					          :vendors="{{ json_encode($vendors) }}"
				></update-ready-sales>
			</div>
		</div>
	</div>
</div>
@stop