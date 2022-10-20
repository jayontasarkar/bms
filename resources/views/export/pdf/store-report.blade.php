@extends('export.default')

@section('content')
<h3 class="text-center" style="text-align: center;">{{ $result }}</h3>
<table class="table card-table table-vcenter text-nowrap" style="width: 100%;" border="1">
    <thead class="active">
        <tr>
            <th>Outlet Name</th>
            <th>Address</th>
            <th>Opening</th>
            <th>Total Sale</th>
            <th>Total Paid</th>
            <th>Total Due</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
        @foreach($outlets as $outlet)
        @php
        $vendor = request('vendor') ? : false;
        $opening = $outlet->transactions->where('type', true)->sum('amount');
        $collections = $outlet->transactions->where('type', false)->sum('amount');
        $amount = $opening + $outlet->totalSalesByVendor($vendor) - $collections;
        @endphp
        <tr>
            <td>
                {{ $outlet->name }}
            </td>
            <td>
                {{ $outlet->address }}
            </td>
            <td>
                {{ number_format($opening) }}/=
            </td>
            <td>
                {{ number_format($outlet->totalSalesByVendor($vendor)) }}/=
            </td>
            <td>
                {{ $collections }}/=
            </td>
            <td>
                {{ number_format($amount) }}/=
            </td>
            <td>
                {{ $amount == 0 ? 'No Due' : '' }}
                {{ $amount > 0 ? 'Due Balance' : '' }}
                {{ $amount < 0 ? 'Over Paid' : '' }}
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-right">
                <strong>Grand Total:</strong>
            </td>
            <td>{{ number_format($grandTotalOpening) }}/=</td>
            <td>{{ number_format($grandTotalSale) }}/=</td>
            <td>{{ number_format($grandTotalPaid) }}/=</td>
            <td>{{ number_format($grandTotalDue) }}/=</td>
            <td></td>
        </tr>
    </tbody>
</table>
@stop
