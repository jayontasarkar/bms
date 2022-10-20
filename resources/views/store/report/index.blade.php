@extends('layouts.backend.master')

@section('content')
@component('layouts.backend.common.page-header')
Store Reporting
@endcomponent
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <form id="search">
                    <div class="form-group">
                        <label for="vendor">Select Vendor</label>
                        <select name="vendor" id="vendor" class="form-control">
                            <option value="">All Vendors</option>
                            @forelse($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ request('vendor') == $vendor->id ? 'selected' : ''}}>
                                {{ $vendor->name }}
                            </option>
                            @empty

                            @endforelse
                        </select>
                    </div>
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
                        <select class="form-control" name="thana" id="thana-selection">
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
                    <div class="form-group text-center mt-5">
                        <a href="{{ url('/store-report') }}" class="btn btn-gray mr-2">
                            <i class="fa fa-close"></i> Clear
                        </a>
                        <button type="submit" class="btn btn-secondary"><i class="fe fe-search"></i> Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="row alert alert-success">
                    <div class="col-md-9 pt-2">
                        {{ $result }}
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" id="filter-table" value="{{ request('search') }}" placeholder="Search by outlet">
                    </div>
                </div>
                @if(count($outlets))
                <div style="margin-bottom: 10px;">
                    <a class="btn btn-secondary" href="#" id="export-pdf-table">
                        <i class="fa fa-file-pdf-o"></i> Export TO PDF
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-bordered table-vcenter text-nowrap datatable">
                        <thead>
                            <tr class="bg-gray-dark">
                                <th>Outlet Name</th>
                                <th>Address</th>
                                <th>Opening</th>
                                <th>Total Sale</th>
                                <th>Total Paid</th>
                                <th>Total Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($outlets as $outlet)
                            @php
                            $vendor = request('vendor') ? : false;
                            $opening = $outlet->transactions->where('type', true)->sum('amount');
                            $collections = $outlet->transactions->where('type', false)->sum('amount');
                            $amount = $opening + $outlet->totalSalesByVendor($vendor) - $collections;
                            $class_name = $amount > 0 ? 'danger' : ($amount < 0 ? 'primary' : 'success' ) ; @endphp <tr class="table-{{ $class_name }}">
                                <td>
                                    <a href="{{ route('outlets.show', [$outlet]) }}">{{ $outlet->name }}</a>
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
                                    {{ number_format($amount) }}/= &nbsp;
                                    {{ $amount == 0 ? '(No Due)' : '' }}
                                    {{ $amount < 0 ? '(Over Paid)' : '' }}
                                </td>
                                </tr>
                                @endforeach
                        </tbody>
                        <tfoot class="bg-gray text-white">
                            <tr>
                                <td colspan="2" class="text-right">
                                    <strong>Grand Total:</strong>
                                </td>
                                <td>{{ number_format($grandTotalOpening) }}/=</td>
                                <td>{{ number_format($grandTotalSale) }}/=</td>
                                <td>{{ number_format($grandTotalPaid) }}/=</td>
                                <td>{{ number_format($grandTotalDue) }}/=</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="d-flex justify-content-end text-center mt-5">
                    {{ $outlets->appends(request()->except('page'))->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@include('layouts.backend.common.datatable', [
'title' => $result . ' on ' . now()->format('m/d/Y'),
'columns' => '[ 0, 1, 2, 3, 4, 5, 6 ]',
'searchCol' => 0,
'paging' => false,
'removeExport' => true,
])

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $("#search").submit(function() {
            $(this).find("select").filter(function() {
                return !this.value;
            }).attr("disabled", "disabled");
            return true;
        });
        $("#filter-table").on('keypress', function(e) {
            const val = $('#filter-table').val();
            if (e.which === 13) {
                const urlParams = new URLSearchParams(window.location.search);
                const vendor = urlParams.get('vendor');
                const district = urlParams.get('district');
                const thana = urlParams.get('thana');
                let route = "{{ route('stores.report.index') }}?";
                if (vendor) route += `vendor=${vendor}&`;
                if (district) route += `district=${district}&`;
                if (thana) route += `thana=${thana}&`;
                if (val) route += `search=${val}&`;
                var strLen = route.length;
                route = route.slice(0, strLen - 1);
                window.location.href = route;
            }
        });
        $("#export-pdf-table").on('click', function(e) {
            e.preventDefault();
            const val = $('#filter-table').val();
            const urlParams = new URLSearchParams(window.location.search);
            const vendor = urlParams.get('vendor');
            const district = urlParams.get('district');
            const thana = urlParams.get('thana');
            let route = "{{ route('stores.report.export-pdf.index') }}?";
            if (vendor) route += `vendor=${vendor}&`;
            if (district) route += `district=${district}&`;
            if (thana) route += `thana=${thana}&`;
            if (val) route += `search=${val}&`;
            var strLen = route.length;
            route = route.slice(0, strLen - 1);
            window.location.href = route;
        });
    })

</script>
@endpush
