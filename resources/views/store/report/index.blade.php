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
                        <input class="form-control" id="filter-table" placeholder="Search by outlet">
                    </div>
                </div>
                @if(count($outlets))
                <div class="table-responsive">
                    <table class="table card-table table-bordered table-vcenter text-nowrap datatable">
                        <thead>
                            <tr class="bg-gray-dark">
                                <th>Outlet Name</th>
                                <th>Proprietor Name</th>
                                <th>Address</th>
                                <th>Opening</th>
                                <th>Total Sale</th>
                                <th>Total Paid</th>
                                <th>Total Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $grandCollection = 0;
                            $grandDue = 0;
                            @endphp
                            @foreach($outlets as $outlet)
                            @php
                            $vendor = request('vendor') ? : false;
                            if(request()->has('vendor')) {
                            $opening = $outlet->transactions->where('type', true)->where('vendor_id', request('vendor'))->sum('amount');
                            $collections = $outlet->transactions->where('type', false)->where('vendor_id', request('vendor'))->sum('amount');
                            }else{
                            $opening = $outlet->transactions->where('type', true)->sum('amount');
                            $collections = $outlet->transactions->where('type', false)->sum('amount');
                            }
                            @endphp
                            @if(($opening + $outlet->totalSalesByVendor($vendor) - $collections) > 0)
                            <tr>
                                <td>
                                    <a href="{{ route('outlets.show', [$outlet]) }}">{{ $outlet->name }}</a>
                                </td>
                                <td>
                                    {{ $outlet->proprietor }}
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
                                    {{ number_format($due = $opening + $outlet->totalSalesByVendor($vendor) - $collections) }}/=
                                </td>
                                @php
                                $grandCollection += $collections;
                                $grandDue += $due;
                                @endphp
                            </tr>
                            @endif
                            @endforeach
                            @if($grandDue > 0)
                        <tfoot class="bg-gray text-light">
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total:</td>
                                <td>
                                    {{ number_format($grandCollection) }}/=
                                </td>
                                <td>
                                    {{ number_format($grandDue) }}/=
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                        </tbody>
                    </table>
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
'searchCol' => 0
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
    })

</script>
@endpush
