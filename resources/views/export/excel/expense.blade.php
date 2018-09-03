@extends('export.default')

@section('content')
	<table>
		<thead>
			<tr style="font-weight: bold;">
				<th>Expense Title (Description)</th>
				<th>Expense Date</th>
				<th>Expense Amount</th>
			</tr>
		</thead>
		<tbody>
			@foreach($expenses as $expense)
			<tr>
				<td>{{ $expense->title }}</td>
				<td>{{ $expense->expense_date->format('M d, Y') }}</td>
				<td>{{ number_format($expense->amount) }}/=</td>
			</tr>
			@endforeach
			<tr>
				<td></td>
				<td><strong>Total Expense:</strong></td>
				<td>{{ number_format($expenses->sum('amount')) }}/=</td>
			</tr>
		</tbody>
	</table>
@stop