<div class="ibox-content panel mb10">
	<form action="{{route('salary-hike-search')}}" method="post" autocomplete="off" id="search-salary-hike">
	@csrf
	<div class="row filter-row" style="padding-top: 10px;padding-bottom: 10px">
		<div class="col-sm-6 col-md-3">
			<select class="chosen-select" id="search_employee" name="user_id">
				<option value="">Select Employee</option>
				@foreach ($employees as $employee)
				<option value="{{$employee->id}}" {{request()->user_id == $employee->id ? 'selected' : ''}}>{{$employee->full_name}}</option>
				@endforeach
			</select>
		</div>

		<div class="col-sm-6 col-md-3">
			<div class="input-group date">
				<span class="input-group-addon">
					<i class="ri-calendar-2-line"></i>
				</span>
				<input type="text" class="form-control salary-hike-datepicker dateInput m-auto" name="year" id="search_year" value="{{ request()->year ?? date('Y') }}"  />
			</div>         
		</div>
	</div>
	</form>
</div>