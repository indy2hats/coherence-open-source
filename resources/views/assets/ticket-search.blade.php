<div class="ibox-content panel mb10">
	<form action="{{route('assets.ticket-raised-asset-search')}}" method="post" autocomplete="off" id="ticket-search-asset">
	@csrf
	<div class="row filter-row" style="padding-top: 10px;padding-bottom: 10px">
		<div class="col-sm-6 col-md-3">
			<select class="chosen-select" id="search_asset_employee" name="asset_id">
				<option value="">Select Asset</option>
				@foreach ($list as $asset)
				<option value="{{$asset->id}}" {{request()->asset_id == $asset->id ? 'selected' : ''}}>{{$asset->full_name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-6 col-md-3">
			<select class="chosen-select" id="search_ticket_employee" name="user_id">
				<option value="">Select Employee</option>
				@foreach ($users as $user)
				<option value="{{$user->id}}" {{request()->user_id == $user->id ? 'selected' : ''}}>{{$user->full_name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-6 col-md-3">
			<select class="chosen-select" id="search_ticket_status" name="status">
				<option value="">Select Status</option>
				<option value="open" {{request()->status === 'open' ? 'selected' : ''}}>Open</option>
				<option value="closed" {{request()->status === 'closed' ? 'selected' : ''}}>Closed</option>
			</select>
		</div>
		<div class="col-sm-6 col-md-3">
			<select class="chosen-select" id="search_resolving_status" name="resolving_status">
				<option value="">Select Resolving Status</option>
				@foreach ($ticketStatus as $status)
				<option value="{{$status->id}}" {{request()->resolving_status == $status->id ? 'selected' : ''}}>{{$status->title}}</option>
				@endforeach
			</select>
		</div>
	</div>
	</form>
</div>