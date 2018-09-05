@extends('layouts.backend.master')

@push('styles')
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
	<style>
		.dataTables_filter {
			display: none; 
		},
		.buttons-excel, .buttons-html5 { margin-bottom: 6px; }
		table.dataTable.no-footer {
		    border-bottom: 1px solid #e1e1e1;
		}
	</style>
@endpush

@section('content')
	@component('layouts.backend.common.page-header')
		Incomplete Payment Sales
	@endcomponent
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<div class="row mb-5">
						<div class="col-md-9">
							<form id="search">
							  <div class="form-row align-items-center">
							    <div class="col-auto">
							      <input type="date" name="from" value="{{ request('from') }}" class="form-control" placeholder="From Date">
							    </div>
							    <div class="col-auto"><label style="font-size: 1.2em;" class="ml-1 mr-1">To</label></div>
							    <div class="col-auto">
							      <input type="date" name="to" value="{{ request('to') }}" class="form-control" placeholder="To Date">
							    </div>
							    <div class="col-auto">
							      <button type="submit" class="btn btn-default"><i class="fe fe-search"></i></button>
							      <a href="{{ route('sales.index') }}" class="btn btn-warning">CLEAR</a>
							    </div>
							  </div>
							</form>
						</div>
						<div class="col-md-3">
							<input type="text" name="" id="filter-table" class="form-control" 
								   placeholder="Search by Sales Order">
						</div>
					</div>
					@if(count($sales))
		              <div class="table-responsive">
		                <table class="table card-table table-vcenter text-nowrap datatable">
		                  <thead class="bg-gray-dark">
		                    <tr>
		                      <th class="w-1">SO. No.</th>
		                      <th>Outlet/Clients</th>
		                      <th>Sales Date</th>
		                      <th>Total Amount</th>
		                      <th>Total Paid</th>
		                      <th>Discount</th>
		                      <th>Due Amount</th>
		                      <th></th>
		                      <th></th>
		                    </tr>
		                  </thead>
		                  <tbody>
		                    @foreach($sales as $sale)
		                      <tr>
		                        <td class="search">
		                        	<a href="{{ route('sales.show', [$sale]) }}">{{ $sale->memo }}</a>
		                        </td>
		                        <td class="search">
		                          <a href="{{ route('sales.show', [$sale->vendor]) }}" class="text-inherit">
		                            {{ $sale->outlet->name }}
		                          </a>
		                        </td>
		                        <td>
		                          {{ $sale->sales_date->format('M d, Y') }}
		                        </td>
		                        <td>
		                          {{ number_format($total = $sale->total_balance) }}/=
		                        </td>
		                        <td>
		                          {{ number_format($paid = $sale->total_paid) }}/=
		                        </td>
		                        <td>
		                          {{ number_format($discount = $sale->total_discount) }}/=
		                        </td>
		                        <td>
		                          {{ number_format($total - $paid - $discount) }}/=
		                        </td>
		                        <td>
		                        	<collection :sales="{{ json_encode($sale) }}"
		                                        :url="'{{ route('sales.transactions.store', [$sale]) }}'" 
		                            ></collection>
		                        </td>
		                        <td>
		                        	<outlet-memo 
											:sales="{{ json_encode($sale) }}"
											:records="{{ json_encode($sale->records) }}"
											:transactions="{{ json_encode($sale->transactions) }}"
											:title="'Show'"
									></outlet-memo>
		                        </td>
		                      </tr>
		                    @endforeach
		                  </tbody>
		                </table>
		              </div>
		            @else
		              <div class="alert alert-warning text-center">
		                <strong>No Sales was found with pending amount</strong>
		              </div>
		            @endif
				</div>
			</div>
		</div>
	</div>
@stop

@push('scripts')
	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
    		var table = $('.datatable').DataTable({
    			responsive: true,
    			pagingType: "full_numbers",
        		ordering: false,
        		pageLength: 50,
        		bInfo : false,
        		columnDefs: [
            		{targets: 'no-sort', orderable: false}
        		],
        		bLengthChange: false,
        		dom: 'Bfrtip',
        		buttons : [{
		            extend : 'excel',
		            title : 'Pending Sales Amount by Sales Order',
		            exportOptions: {
	                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                }
		        },
		        {
		            extend : 'pdf',
		            title : 'Pending Sales Amount by Sales Order',
		            exportOptions: {
	                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                }
		        }]
    		});
    		$("#filter-table").on('keyup', function(){
	            table.columns("0").search(this.value).draw();
	        });
	        $("#search").submit(function() {
		      $(this).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
		      return true;
		    });
		});
	</script>
@endpush