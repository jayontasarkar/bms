@extends('layouts.backend.master')
@section('content')
	@component('layouts.backend.common.page-header')
	Vendor Management
	@slot('rightContent')
		<create-vendor></create-vendor>
	@endslot	
	@endcomponent
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					@if(count($vendors))
					<div class="table-responsive">
						<table class="table card-table table-bordered table-vcenter text-nowrap" border="1">
							<thead>
								<tr class="bg-gray-dark">
									<th class="w-1">No.</th>
									<th>Vendor Name</th>
									<th>Address</th>
									<th>Phone No.</th>
									<th>Balance</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($vendors as $key => $vendor)
								<tr>
									<td>
										<span class="text-muted">
											{{ $key + 1 }}
										</span>
									</td>
									<td>
										<a href="{{ route('vendors.show', [$vendor]) }}">{{ $vendor->name }}</a>
									</td>
									<td>
										{{ $vendor->address }}
									</td>
									<td>
										{{ $vendor->phone }}
									</td>
									<td>
										{{ number_format($vendor->openingBalances() ? $vendor->openingBalances()->amount : 0) }}/=
									</td>
									<td>
										<ul class="list-inline mt-3">
											<li class="list-inline-item">
												<edit-vendor :url="'{{ route('vendors.update', [$vendor]) }}'"
															 :vendor="{{ json_encode($vendor) }}"
															 :opening-balance="{{ json_encode($vendor->openingBalances()) }}"
												></edit-vendor>
											</li>
										</ul>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					@else
						<div class="alert alert-danger text-center">
							<strong>Didn't find any vendor with the search criteria.</strong>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@stop