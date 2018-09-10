@push('styles')
	<link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endpush
@push('scripts')
	<script src="{{ asset('js/datatable.js') }}"></script>
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
		            title : "{{ isset($title) ? $title : time() }}",
		            exportOptions: {
	                    columns: {{ $columns }}
	                }
		        },
		        {
		            extend : 'pdf',
		            title : "{{ isset($title) ? $title : time() }}",
		            exportOptions: {
	                    columns: {{ $columns }}
	                }
		        }]
    		});
    		$("#filter-table").on('keyup', function(){
	            table.columns("{{ isset($searchCol) ? $searchCol : '0' }}").search(this.value).draw();
	        });
		});
	</script>
@endpush