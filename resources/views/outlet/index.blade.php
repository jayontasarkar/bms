@extends('layouts.backend.master')
@section('content')
@component('layouts.backend.common.page-header')
Outlet Management
@endcomponent
<div class="row">
	<div class="col-md-3">
		<div class="card">
			<div class="card-header">
				<create-outlet :districts="{{ json_encode($districts) }}"></create-outlet>
			</div>
			<div class="card-body">
				<div class="form-group">
					<label for="district-selection"><strong>Select district</strong></label>
					<select class="form-control" name="district" id="district-selection">
						<option value="">All districts</option>
						@forelse($districts as $district)
							<option value="{{ $district->id }}" {{ request('district') == $district->id ? 'selected' : '' }}>
								{{ $district->name }}
							</option>
						@empty	
						@endforelse
					</select>
				</div>
				@if(request()->has('district') && request('district') != '')
					@php
						$thanas = $districts->filter(function($item) {
						    return $item->id == request('district');
						})->first()->thanas;
					@endphp
					<div class="form-group">
						<label for="thana-selection"><strong>Select thana</strong></label>
						<select class="form-control" name="district" id="thana-selection">
							<option value="">All thana</option>
							@forelse($thanas as $thana)
								<option value="{{ $thana->id }}" {{ request('thana') == $thana->id ? 'selected' : '' }}>
									{{ $thana->name }}
								</option>
							@empty	
							@endforelse
						</select>
					</div>
				@endif	
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="card">
			<div class="card-body">
				@if(count($outlets))
					<div class="table-responsive">
						<table class="table card-table table-bordered table-vcenter text-nowrap" border="1">
							<thead>
								<tr class="bg-gray-dark">
									<th class="w-1">No.</th>
									<th>Outlet Name</th>
									<th>Proprietor Name</th>
									<th>Phone No.</th>
									<th>Address</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($outlets as $key => $outlet)
								<tr>
									<td>
										<span class="text-muted">
											{{ 
												(request('page') != null && request('page') != 1 ? 
													config('bms.items_per_page') * (request('page') - 1) : 
													1)  + $key 
											}}
										</span>
									</td>
									<td>
										<a href="{{ route('outlets.show', [$outlet]) }}">{{ $outlet->name }}</a>
									</td>
									<td>
										{{ $outlet->proprietor }}
									</td>
									<td>
										{{ $outlet->phone }}
									</td>
									<td>
										{{ $outlet->address }}, {{ $outlet->thana->name }}, 
										{{ $outlet->thana->district->name }}
									</td>
									<td>
										<ul class="list-inline mt-3">
											<li class="list-inline-item">
												<edit-outlet :districts="{{ json_encode($districts) }}"
															 :outlet="{{ json_encode($outlet) }}"
															 :url="'{{ route('outlets.update', [$outlet]) }}'"
												></edit-outlet>
											</li>
											<li class="list-inline-item">
												<remove-btn :url="'{{ route('outlets.destroy', [$outlet]) }}'"></remove-btn>	
											</li>
										</ul>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="justify-content-center align-tiems-center text-center">
						{{ $outlets->appends(request()->except('page'))->links() }}
					</div>
				@else
					<div class="alert alert-danger text-center">
						<strong>No outlet was found with the criteria.</strong>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@stop

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#district-selection').change(function(){
				if($(this).val() == '') {
					window.location.href = "{{ route('outlets.index') }}";
				}else{
					window.location.href = "{{ route('outlets.index') }}?district=" + $(this).val();
				}
			});
			$('#thana-selection').change(function(){
				if($(this).val() == '') {
					window.location.href = "{{ route('outlets.index', ['district' => request('district')]) }}";
				}else{
					window.location.href = "{{ route('outlets.index') }}?district=" + {{ request('district') }} + "&thana=" + $(this).val();
				}
			});
		});
	</script>
@endpush