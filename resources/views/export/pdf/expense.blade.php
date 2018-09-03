@extends('export.default')

@section('content')
	<h3 class="text-center" style="text-align: center;">Expense Bills - {{ search_options() }}</h3>
	<table class="table card-table table-vcenter text-nowrap" style="width: 100%;" border="1">
		<thead class="active">
			<tr>
				<th>#</th>
				<th>Expense Title (Description)</th>
				<th>Expense Date</th>
				<th>Expense Amount</th>
			</tr>
		</thead>
		<tbody>
			@foreach($expenses as $key => $expense)
			<tr>
				<td>{{ $key + 1 }}</td>
				<td>{{ $expense->title }}</td>
				<td>{{ $expense->expense_date->format('M d, Y') }}</td>
				<td>{{ number_format($expense->amount) }}/=</td>
			</tr>
			@endforeach
			<tr>
				<td></td>
				<td></td>
				<td><strong>Total Expense:</strong></td>
				<td>{{ number_format($expenses->sum('amount')) }}/=</td>
			</tr>
		</tbody>
	</table>
@stop