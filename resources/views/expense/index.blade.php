@extends('layouts.backend.master')
@section('content')
@component('layouts.backend.common.page-header')
Expense Management
@endcomponent
<div class="row">
	<div class="col-md-3">
		<div class="card">
			<div class="card-header">
				<create-expense :vendors="{{ json_encode($vendors) }}"></create-expense>
			</div>
			<div class="card-body">
				<div class="form-group">
                    <label class="form-label">Search by day(s)</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item select-days">
                        <input type="radio" name="days" value="1" 
                        		class="selectgroup-input" {{ request('days') == 1 ? 'checked' : '' }}
                        >
                        <span class="selectgroup-button">1</span>
                      </label>
                      <label class="selectgroup-item select-days">
                        <input type="radio" name="days" value="7" 
                        		class="selectgroup-input" {{ request('days') == 7 ? 'checked' : '' }}
                        >
                        <span class="selectgroup-button">7</span>
                      </label>
                      <label class="selectgroup-item select-days">
                        <input type="radio" name="days" value="15" 
                        		class="selectgroup-input" {{ request('days') == 15 ? 'checked' : '' }}
                        >
                        <span class="selectgroup-button">15</span>
                      </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Search by month(s)</label>
                    <div class="selectgroup w-100">
                      @for($i = 1; $i <= 4; $i++)	
	                      <label class="selectgroup-item select-months">
	                        <input type="radio" name="months" value="{{ $i }}" 
	                        		class="selectgroup-input" {{ request('months') == $i ? 'checked' : '' }}
	                        >
	                        <span class="selectgroup-button">{{ $i }}</span>
	                      </label>
                      @endfor
                    </div>
                    <div class="selectgroup w-100">
                      @for($i = 5; $i <= 8; $i++)	
	                      <label class="selectgroup-item select-months">
	                        <input type="radio" name="months" value="{{ $i }}" 
	                        		class="selectgroup-input" {{ request('months') == $i ? 'checked' : '' }}
	                        >
	                        <span class="selectgroup-button">{{ $i }}</span>
	                      </label>
                      @endfor
                    </div>
                    <div class="selectgroup w-100">
                      @for($i = 9; $i <= 12; $i++)	
	                      <label class="selectgroup-item select-months">
	                        <input type="radio" name="months" value="{{ $i }}" 
	                        		class="selectgroup-input" {{ request('months') == $i ? 'checked' : '' }}
	                        >
	                        <span class="selectgroup-button">{{ $i }}</span>
	                      </label>
                      @endfor
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><strong>Search by date(s)</strong></label>
                    <form id="search">
                    	<div class="form-group">
                    		<label for="vendor">Select vendor</label>
                    		<select name="vendor" id="vendor" class="form-control">
                    			<option value="">All vendors</option>
                    			@forelse($vendors as $vendor)
									<option value="{{ $vendor->id }}" {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
										{{ $vendor->name }}
									</option>
                    			@empty
                    			@endforelse
								<option value="other">Other Expenses</option>
                    		</select>
                    	</div>
                    	<div class="form-group">
							<label class="selectgroup-item">From Date</label>
							<input type="date" name="from" value="{{ request('from') }}" class="form-control">
	                    </div>
	                    <div class="form-group">
							<label class="selectgroup-item">To Date</label>
							<input type="date" name="to" value="{{ request('to') }}" class="form-control">
	                    </div>
	                    <div class="form-group text-center">
	                    	<a href="{{ route('expenses.index') }}" class="btn btn-sm btn-secondary">
	                    		<i class="fe fe-x-circle"></i> Clear
	                    	</a> 
	                    	<button type="submit" class="btn btn-sm btn-info">
	                    		<i class="fe fe-search"></i> Search
	                    	</button>
	                    </div>
                    </form>
                </div>
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="card">
			<div class="card-body">
				@if(count($expenses))
					<div class="alert alert-success">
						<div class="row">
							<div class="col-md-4">
								<strong>
									Total Expense: {{ number_format($counter['total']) }}/=
								</strong>
							</div>
							<div class="col-md-6 text-center">
								{{ search_options() }}
							</div>
							<div class="col-md-2">
								<strong class="pull-right">
									Total Item: {{ $counter['qty'] }}
								</strong>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table card-table datatable table-bordered table-vcenter text-nowrap" border="1">
							<thead>
								<tr class="bg-gray-dark">
									<th class="w-1">No.</th>
									<th>Expense Title</th>
									<th>Expense Date</th>
									<th>Expense Amount</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($expenses as $key => $expense)
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
										{{ $expense->title }}
									</td>
									<td>
										{{ $expense->expense_date->format('M d, Y') }}
									</td>
									<td class="text-center">
										{{ number_format($expense->amount) }}/=
									</td>
									<td>
										<ul class="list-inline mt-3">
											<li class="list-inline-item">
												<edit-expense :expense="{{ json_encode($expense) }}"
															  :url="'{{ route('expenses.update', [$expense]) }}'"
															  :vendors="{{ json_encode($vendors) }}"	
												></edit-expense>
											</li>
											<li class="list-inline-item">
												<remove-btn :url="'{{ route('expenses.destroy', [$expense]) }}'"></remove-btn>	
											</li>
										</ul>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="justify-content-center align-tiems-center text-center">
						{{ $expenses->appends(request()->except('page'))->links() }}
					</div>
				@else
					<div class="alert alert-danger text-center" role="alert">
						<strong>No expense was found in the search criteria</strong>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@stop

@include('layouts.backend.common.datatable', [
	'title' => search_options(),
	'columns' => '[1, 2, 3]',
])

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.select-days').click(function(e){
				e.preventDefault();
				let noOfDays = $(this).children('input[name=days]').val();
				window.location.href="{{ route('expenses.index') }}?days=" + noOfDays;
			});
			$('.select-months').click(function(e){
				e.preventDefault();
				let noOfMonths = $(this).children('input[name=months]').val();
				window.location.href="{{ route('expenses.index') }}?months=" + noOfMonths;
			});
			$("#search").submit(function() {
		      $(this).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
		      $(this).find("select").filter(function(){ return !this.value; }).attr("disabled", "disabled");
		      return true;
		    });
		});
	</script>
@endpush