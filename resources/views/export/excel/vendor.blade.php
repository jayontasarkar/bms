@extends('export.default')

@section('content')
	<table>
		<thead>
			<tr style="font-weight: bold;">
				<th>Memo</th>
				<th>Purchase Date</th>
				<th>Total Amount</th>
				<th>Total Paid</th>
				<th>Total Discount</th>
				<th>Total Due</th>
			</tr>
		</thead>
		<tbody>
			@foreach($purchases as $purchase)
			<tr>
				<td>{{ $purchase->memo }}</td>
				<td>{{ $purchase->purchase_date->format('M d, Y') }}</td>
				<td>{{ number_format($purchase->total_balance) }}/=</td>
				<td>{{ number_format($purchase->total_paid) }}/=</td>
				<td>{{ number_format($purchase->total_discount) }}/=</td>
				<td>{{ number_format($purchase->total_balance - $purchase->total_paid - $purchase->total_discount) }}/=</td>
			</tr>
			@endforeach
			<tr>
				<td></td>
				<td><strong>Total :</strong></td>
				<td>{{ number_format($total = $purchases->sum('total_balance')) }}/=</td>
				<td>{{ number_format($paid = $purchases->sum('total_paid')) }}/=</td>
				<td>{{ number_format($discount = $purchases->sum('total_discount')) }}/=</td>
				<td>{{ number_format($total - $paid - $discount) }}/=</td>
			</tr>
		</tbody>
	</table>
@stop