<form id="search">
	<div class="form-group">
		<label for="vendor">Select Vendor</label>
		<select name="vendor" id="vendor" class="form-control">
			<option value="">All Vendors</option>
			@forelse($vendors as $vendor)
				<option value="{{ $vendor->id }}" 
					{{ request('vendor') == $vendor->id ? 'selected' : ''  }}
				>{{ $vendor->name }}</option>
			@empty
			@endforelse	
		</select>
	</div>
    <div class="form-group">
    	<label for="from">From Date</label>
        <input type="date" name="from" value="{{ request('from') }}" class="form-control" placeholder="From Date">
    </div>
    <div class="form-group">
    	<label for="to">To Date</label>
        <input type="date" name="to" value="{{ request('to') }}" class="form-control" placeholder="To Date">
    </div>
    <div class="form-group text-center">
      <a href="{{ $route }}" class="btn btn-gray mr-2">CLEAR</a>
      <button type="submit" class="btn btn-info"><i class="fe fe-search"></i> Search</button>
    </div>
</form>