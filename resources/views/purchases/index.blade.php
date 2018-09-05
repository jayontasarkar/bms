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
		Incomplete Payment Pruchases
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
							      <a href="{{ route('purchases.index') }}" class="btn btn-warning">CLEAR</a>
							    </div>
							  </div>
							</form>
						</div>
						<div class="col-md-3">
							<input type="text" name="" id="filter-table" class="form-control" 
								   placeholder="Search by Purchase Order">
						</div>
					</div>
					@if(count($purchases))
		              <div class="table-responsive">
		                <table class="table card-table table-vcenter text-nowrap datatable">
		                  <thead class="bg-gray-dark">
		                    <tr>
		                      <th class="w-1">PO. No.</th>
		                      <th>Vendor/Suppliers</th>
		                      <th>Purchase Date</th>
		                      <th>Total Amount</th>
		                      <th>Total Paid</th>
		                      <th>Discount</th>
		                      <th>Due Amount</th>
		                      <th></th>
		                      <th></th>
		                    </tr>
		                  </thead>
		                  <tbody>
		                    @foreach($purchases as $purchase)
		                      <tr>
		                        <td class="search">
		                        	<a href="{{ route('purchases.show', [$purchase]) }}"> {{ $purchase->memo }}</a>
		                        </td>
		                        <td class="search">
		                          <a href="{{ route('vendors.show', [$purchase->vendor]) }}" class="text-inherit">
		                            {{ $purchase->vendor->name }}
		                          </a>
		                        </td>
		                        <td>
		                          {{ $purchase->purchase_date->format('M d, Y') }}
		                        </td>
		                        <td>
		                          {{ number_format($total = $purchase->total_balance) }}/=
		                        </td>
		                        <td>
		                          {{ number_format($paid = $purchase->total_paid) }}/=
		                        </td>
		                        <td>
		                          {{ number_format($discount = $purchase->total_discount) }}/=
		                        </td>
		                        <td>
		                          {{ number_format($total - $paid - $discount) }}/=
		                        </td>
		                        <td>
		                          <payment :purchase="{{ json_encode($purchase) }}"
		                                   :url="'{{ route('purchases.transactions.store', [$purchase]) }}'" 
		                          ></payment>
		                        </td>
		                        <td>
		                        	<vendor-memo 
											:purchase="{{ json_encode($purchase) }}"
											:records="{{ json_encode($purchase->records) }}"
											:transactions="{{ json_encode($purchase->transactions) }}"
											:title="'Show'"
									></vendor-memo>
		                        </td>
		                      </tr>
		                    @endforeach
		                  </tbody>
		                </table>
		              </div>
		            @else
		              <div class="alert alert-warning text-center">
		                <strong>No purchase was found with pending amount</strong>
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
		            title : 'Pending Purchase Amount by Purchase Order',
		            exportOptions: {
	                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
	                }
		        },
		        {
		            extend : 'pdf',
		            title : 'Pending Purchase Amount by Purcahse Order',
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