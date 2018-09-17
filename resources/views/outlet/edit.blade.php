@extends('layouts.backend.master')

@section('content')
@component('layouts.backend.common.page-header')
	Edit Outlet: {{ $outlet->name }}
	@slot('rightContent')
		<a href="{{ route('outlets.show', [$outlet]) }}" class="btn btn-sm btn-info ml-auto mr-3 mt-4">
			<i class="fa fa-search-plus mr-1"></i> Go outlet page
		</a>
		<a href="#" onClick="history.go(-1); return false;" class="btn btn-sm btn-gray mt-4">
			<i class="fe fe-corner-down-left mr-1"></i> Back
		</a>
	@endslot
@endcomponent
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card">
			<div class="card-body">
				<update-outlet :url="'{{ route('outlets.update', [$outlet]) }}'"
				             :districts="{{ json_encode($districts) }}"
				             :outlet="{{ json_encode($outlet) }}"
				             :transactions="{{ json_encode($outlet->openingBalances()) }}"
				             :vendors="{{ json_encode($vendors) }}"
				></update-outlet>
			</div>
		</div>
	</div>
</div>
@stop