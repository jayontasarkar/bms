@extends('layouts.backend.master')

@section('content')
	@component('layouts.backend.common.page-header')
		Banking Management
	@endcomponent
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-hearder">
					<manage-bank :url="'{{ route('bankings.store') }}'" :banks="{{ json_encode($banks) }}"></manage-bank>
				</div>
				<div class="card-body">
					<form id="search-bank" method="GET">
						<div class="form-group">
							<label for="vendor" style="width: 100%;">
								<strong>Search by bank & date</strong>
								<a href="{{ route('bankings.index') }}" class="float-right btn btn-sm btn-secondary">
		                    		<i class="fe fe-x-circle"></i> Clear
		                    	</a> 
							</label>
							<select class="form-control mt-2" name="bank">
								<option value="">All banks</option>
								@forelse($banks as $bank)
									<option value="{{ $bank->id }}" {{ request('bank') ==  $bank->id ? 'selected' : ''}}>
										{{ $bank->name . ($bank->branch ? ', ' . $bank->branch : '') }}
									</option>
								@empty

								@endforelse
							</select>
						</div>
						<div class="form-group">
							<label for="from">From Date</label>
							<input type="date" class="form-control" name="from" id="from" value="{{ request('from') }}">
						</div>
						<div class="form-group">
							<label for="to">To Date</label>
							<input type="date" class="form-control" name="to" id="to" value="{{ request('to') }}">
						</div>
						<div class="form-group text-center">
							<button type="submit" class="btn btn-default btn-sm">
								<i class="fe fe-search mr-2"></i> Search
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-body">
					@if(count($bankings))
						<div class="table-responsive">
							<table class="table card-table table-bordered table-vcenter text-nowrap" border="1">
								<tbody>
									@foreach($bankings as $bank)
										<tr class="bg-gray-dark">
											<td colspan="6">
												<strong style="color: #fff;">{{ $bank->name . ', ' . $bank->branch }} ({{ $bank->account_no }})</strong>
											</td>
										</tr>
										@forelse($bank->transactions as $key => $transaction)
											<tr>
												<td>{{ $key + 1 }}</td>
												<td>{{ $transaction->comment ? : ($transaction->type ? 'BALANCE DEPOSITED INTO ACCOUNT' : 'BALANCE WITHDRAWED FROM ACCOUNT') }}</td>
												<td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
												<td>
													{{ number_format($transaction->amount) }}/=
												</td>
												<td>
													<span class="badge badge-{{ $transaction->type ? 'success' : 'danger' }}">
														{{ $transaction->type ? 'Deposit' : 'Withdraw' }}
													</span>
												</td>
												<td>
													<edit-bank-transaction 
															:banks="{{ json_encode($banks) }}"
															:url="'{{ route('transactions.update', [$transaction]) }}'"
															:data-info="{{ json_encode($transaction) }}"
													></edit-bank-transaction>
												</td>
											</tr>	
										@empty
											<tr>
												<td colspan="5">
													<div class="alert alert-danger text-center">
														No withdraw/deposit was found within the criteria
													</div>
												</td>
											</tr>	
										@endforelse
										@if(count($bank->transactions))
											<tr>
												@php
													$deposit = $bank->transactions->where('type', true)->sum('amount');
													$withdraw = $bank->transactions->where('type', false)->sum('amount');
												@endphp
												<td colspan="3">
													<span>(Total Deposit: {{ number_format($deposit) }}/= & Total Withdraw: {{ number_format($withdraw) }}/=)</span>
													<strong class="float-right">Balance in Account:</strong>
												</td>
												<td>
													{{ number_format($deposit - $withdraw) }}/=
												</td>
												<td colspan="2">
												</td>
											</tr>
										@endif
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<div class="alert alert-danger text-center" role="alert">
							<strong>No withdraw/deposit was found within the criteria</strong>
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
			$("#search-bank").submit(function() {
		        $(this).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
		      return true;
		    });
		});
	</script>
@endpush