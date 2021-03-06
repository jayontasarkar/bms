@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Products Management
	@endcomponent
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<create-product :vendors="{{ json_encode($vendors) }}"></create-product>
						</div>
						<div class="col-md-6">
							<select class="form-control" name="vendor" id="select-vendor">
								<option value="">Select vendor</option>
								@forelse($vendors as $vendor)
									<option value="{{ $vendor->id }}" {{ request('vendor') ==  $vendor->id ? 'selected' : ''}}>
										{{ $vendor->name }}
									</option>
								@empty

								@endforelse
							</select>
						</div>
						<div class="col-md-2 text-center">
							<a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">
	                    		<i class="fe fe-x-circle"></i> Clear
	                    	</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					@if(count($products))
						<div class="alert alert-success">
							{{ $search }}
						</div>
						<div class="table-responsive">
							<table class="table card-table datatable table-bordered table-vcenter text-nowrap" border="1">
								<thead>
									<tr class="bg-gray-dark">
										<th>Code</th>
										<th>Product Title</th>
										<th>Vendor Name</th>
										<th>Stock</th>
										<th>Unit Price</th>
										<th>Stock Price</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($products as $key => $product)
										<tr>
											<td>
												<a href="{{ route('products.show', [$product]) }}">{{ $product->code }}</a>
											</td>
											<td>
												<a href="{{ route('products.show', [$product]) }}">{{ $product->title }}</a>
											</td>
											<td class="text-center">
												<a href="{{ route('vendors.show', [$product->vendor]) }}">
													{{ $product->vendor->name }}
												</a>
											</td>
											<td>
												{{ $product->stock }} PIECE
											</td>
											<td>{{ number_format($product->unit_price) }}/=</td>
											<td>
												{{ number_format($product->stock * $product->unit_price) }}/=
											</td>
											<td>
												<ul class="list-inline mt-3">
													<li class="list-inline-item">
														<edit-product :product="{{ json_encode($product) }}"
																	  :url="'{{ route('products.update', [$product]) }}'"
																	  :vendors="{{ json_encode($vendors) }}"
														></edit-product>
													</li>
													<li class="list-inline-item">
														<remove-btn :url="'{{ route('products.destroy', [$product]) }}'"></remove-btn>
													</li>
												</ul>
											</td>
										</tr>
									@endforeach
									<tfoot>
										<tr class="bg-light">
											<td></td>
											<td></td>
											<td><strong>Total Stock Price:</strong></td>
											<td></td>
											<td></td>
											@php
												$sum = $products->sum(function($query){
													return $query->unit_price * $query->stock;
												});
											@endphp
											<td>{{ number_format($sum) }}/=</td>
											<td></td>
										</tr>
									</tfoot>
								</tbody>
							</table>
						</div>
					@else
						<div class="alert alert-danger text-center" role="alert">
							<strong>No product was found in the search criteria</strong>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@stop

@include('layouts.backend.common.datatable', [
	'title' => $search,
	'columns' => '[ 0, 1, 2, 3, 4, 5]',
	'searchCol' => 1
])

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#select-vendor').change(function(e){
				e.preventDefault();
				if($(this).val() != '') {
					window.location.href="{{ route('products.index') }}?vendor=" + $(this).val();
				}
			});
		});
	</script>
@endpush