<div class="ibox-content panel mb10">
	<form action="{{route('assets.employee-ticket-raised-asset-search')}}" method="post" autocomplete="off" id="employee-ticket-search-asset">
	@csrf
	<div class="row filter-row" style="padding-top: 10px;padding-bottom: 10px">
		<div class="col-sm-6 col-md-3">
			<select class="chosen-select" id="employee_search_ticket_status" name="status">
				<option value="">Select Status</option>
				<option value="open" {{request()->status === 'open' ? 'selected' : ''}}>Open</option>
				<option value="closed" {{request()->status === 'closed' ? 'selected' : ''}}>Closed</option>
			</select>
		</div>
	</div>
	</form>
</div>