@extends('export.default')

@section('content')
	<table>
		<thead>
			<tr style="font-weight: bold;">
				<th>Memo</th>
				<th>Delivery Date</th>
				<th>Total Amount</th>
				<th>Total Paid</th>
				<th>Total Discount</th>
				<th>Total Due</th>
			</tr>
		</thead>
		<tbody>
			@foreach($sales as $sale)
			<tr>
				<td>{{ $sale->memo }}</td>
				<td>{{ $sale->sales_date->format('M d, Y') }}</td>
				<td>{{ number_format($sale->total_balance) }}/= {!! $purchase->type ? ' (Opening)' : '' !!}</td>
				<td>{{ number_format($sale->total_paid) }}/=</td>
				<td>{{ number_format($sale->total_discount) }}/=</td>
				<td>{{ number_format($sale->total_balance - $sale->total_paid - $sale->total_discount) }}/=</td>
			</tr>
			@endforeach
			<tr>
				<td></td>
				<td><strong>Total :</strong></td>
				<td>{{ number_format($total = $sale->sum('total_balance')) }}/=</td>
				<td>{{ number_format($paid = $sale->sum('total_paid')) }}/=</td>
				<td>{{ number_format($discount = $sale->sum('total_discount')) }}/=</td>
				<td>{{ number_format($total - $paid - $discount) }}/=</td>
			</tr>
		</tbody>
	</table>
@stop