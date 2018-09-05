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
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					
				</div>
			</div>
		</div>
	</div>
@stop