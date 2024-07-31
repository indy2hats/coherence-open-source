<div class="ibox-content panel mb10">
	<form action="{{route('assets.asset-search')}}" method="post" autocomplete="off" id="search-asset">
	@csrf
	<div class="row filter-row" style="padding-top: 10px;padding-bottom: 10px">
		<div class="col-sm-6 col-md-2" style="margin-bottom: 10px;">
			<select class="chosen-select asset-filter" id="search_type" name="type_id">
				<option value="">Select Type</option>
				@foreach ($allAssetTypes as $type)
				<option value="{{$type->id}}" {{request()->type_id == $type->id ? 'selected' : ''}}>{{$type->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-6 col-md-2" style="margin-bottom: 10px;">
			<select class="chosen-select asset-filter" id="search_vendor" name="vendor_id">
				<option value="">Select Vendor</option>
				@foreach ($allAssetVendors as $vendor)
				<option value="{{$vendor->id}}" {{request()->vendor_id == $vendor->id ? 'selected' : ''}}>{{$vendor->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-6 col-md-3" style="margin-bottom: 10px;">
			<select class="chosen-select asset-filter" id="search_asset" name="asset_id">
				<option value="">Select Asset</option>
				@foreach ($list as $asset)
				<option value="{{$asset->id}}" {{request()->asset_id == $asset->id ? 'selected' : ''}}>{{$asset->full_name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-6 col-md-3" style="margin-bottom: 10px;">
			<select class="chosen-select asset-filter" id="search_employee" name="user_id">
				<option value="">Select Employee</option>
				@foreach ($users as $user)
				<option value="{{$user->id}}" {{request()->user_id == $user->id ? 'selected' : ''}}>{{$user->full_name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-6 col-md-2" style="margin-bottom: 10px;">
			<select class="chosen-select asset-filter" id="search_status" name="status">
				<option value="">Select Status</option>
				<option value="allocated" {{request()->status === 'allocated' ? 'selected' : ''}}>Allocated</option>
				<option value="non_allocated" {{request()->status === 'non_allocated' ? 'selected' : ''}}>Non Allocated</option>
				<option value="ticket_raised" {{request()->status === 'ticket_raised' ? 'selected' : ''}}>Ticket Raised</option>
				<option value="inactive" {{request()->status === 'inactive' ? 'selected' : ''}}>In Active</option>
			</select>
		</div>
		<div class="col-sm-6 col-md-2 weekrange" style="margin-bottom: 10px;">
			<div class="form-group">
				<input class="form-control weekpicker dateInput" type="text" name="daterange" id="daterange" placeholder="Assigned Date Range" style="height: 30px;border-radius: 5px" value="{{request()->daterange ?? ''}}"/>
			</div>
    	</div>
	</div>

	<div class="ibox-title" id="filter-section">
		<h5>Filter based on attributes</h5>
		<div class="ibox-tools">
			<i class="fa fa-chevron-down"></i>
		</div>
    </div>
	<div class="row filter-row filter-area" style="padding-top: 10px;padding-bottom: 10px;">
	@foreach($attributes as $attribute)
		<div class="col-sm-6 col-md-2" style="margin-bottom: 10px;">
			<select class="chosen-select asset-filter" id="search_{{$attribute->name}}" name="attribute_value_ids">
				<option value="">Select {{ $attribute->name }}</option>
				@foreach ($attribute->attribute_values as $attributeValue)
				<option value="{{ $attributeValue->id }}" {{ (request()->attribute_value_ids && in_array($attributeValue->id, request()->attribute_value_ids)) ? 'selected' : '' }}>
    {{ $attributeValue->value }}
</option>

				@endforeach
			</select>
		</div>
	@endforeach
		
	</div>
	<div class="form-group text-right">
		<button class="btn  btn-w-m btn-info filter-reset">Clear Filters</button>
		<button href="#" class="btn btn-w-m btn-success" id="export-assets"><i class="ri-download-line"></i> Export Assets </button>
	</div>
	</form>
</div>