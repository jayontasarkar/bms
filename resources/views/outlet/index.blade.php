@extends('layouts.backend.master')

@section('content')
@component('layouts.backend.common.page-header')
Outlet Management
@endcomponent
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <create-outlet :districts="{{ json_encode($districts) }}"></create-outlet>
            </div>
            <div class="card-body">
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
                    <select class="form-control" name="district" id="thana-selection">
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
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-success">
                    <div class="row">
                        <div class="col-md-8 pt-2"><strong>{{ $result }}</strong></div>
                        <div class="col-md-4">
                            <input class="form-control" value="{{ request('search') }}" id="filter-table" placeholder="Search by outlet">
                        </div>
                    </div>
                </div>
                @if(count($outlets))
                <div class="table-responsive">
                    <table class="table card-table table-bordered table-vcenter text-nowrap datatable">
                        <thead>
                            <tr class="bg-gray-dark">
                                <th>#</th>
                                <th>Outlet Name</th>
                                <th>Proprietor Name</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($outlets as $index => $outlet)
                            <tr>
                                <td>
                                    {{ request('page') ? ((request('page') -  1) * config('bms.items_per_page')) + $index + 1 : $index + 1  }}
                                </td>
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
                                    <ul class="list-inline mt-3">
                                        <li class="list-inline-item">
                                            <a href="{{ route('outlets.edit', [$outlet]) }}" class="btn btn-xs btn-info">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="#" class="btn btn-xs btn-danger">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end text-center mt-5">
                    {{ $outlets->appends(request()->except('page'))->links() }}
                </div>
                @else
                <div class="alert alert-danger text-center">
                    <strong>No outlet was found with the criteria.</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@include('layouts.backend.common.datatable', [
'title' => "{{ $result }}",
'columns' => '[ 0, 1, 2, 3, 4 ]',
'searchCol' => 0,
'paging' => false,
])

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#district-selection').change(function() {
            if ($(this).val() == '') {
                window.location.href = "{{ route('outlets.index') }}";
            } else {
                window.location.href = "{{ route('outlets.index') }}?district=" + $(this).val();
            }
        });
        $('#thana-selection').change(function() {
            if ($(this).val() == '') {
                window.location.href = "{{ route('outlets.index', ['district' => request('district')]) }}";
            } else {
                window.location.href = "{{ route('outlets.index') }}?district={{ request('district') }}&thana=" + $(this).val();
            }
        });
        $("#filter-table").on('keypress', function(e) {
            const val = $('#filter-table').val();
            if (e.which === 13) {
                const urlParams = new URLSearchParams(window.location.search);
                const thana = urlParams.get('thana');
                const district = urlParams.get('district');
                let route = "{{ route('outlets.index') }}?";
                if (thana) route += `thana=${thana}&`;
                if (district) route += `district=${district}&`;
                if (val) route += `search=${val}&`;
                var strLen = route.length;
                route = route.slice(0, strLen - 1);
                window.location.href = route;
            }
        });

    });

</script>
@endpush
