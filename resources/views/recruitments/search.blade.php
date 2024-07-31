<!-- Search Filter -->
<form action="{{ route('searchCandidate') }}" method="post" autocomplete="off" id="search-candidate">
        @csrf
	<div class="row filter-row" style="padding: 0px 0px 10px 0px">
		<div class="col-sm-5 col-md-5">
			 <select class="chosen-select" id="filter_category" name="filter_category">
	            <option value="" {{request()->filter_category == '' ? 'selected' : ''}}>Select Category</option>
	            <option value="Front End Developer"  {{request()->filter_category == 'Front End Developer' ? 'selected' : ''}}>Front End Developer</option>
	            <option value="PHP Developer"  {{request()->filter_category == 'PHP Developer' ? 'selected' : ''}}>PHP Developer</option>
	            <option value="React Native Developer"  {{request()->filter_category == 'React Native Developer' ? 'selected' : ''}}>React Native Developer</option>
				<option value="QA"  {{request()->filter_category == 'QA' ? 'selected' : ''}}>QA</option>
				<option value="Digital Marketing Executive"  {{request()->filter_category == 'Digital Marketing Executive' ? 'selected' : ''}}>Digital Marketing Executive</option>
	            <option value="Business Development Executive"  {{request()->filter_category == 'Business Development Executive' ? 'selected' : ''}}>Business Development Executive</option>
				<option value="Wordpress Developer"  {{request()->filter_category == 'Wordpress Developer' ? 'selected' : ''}}>Wordpress Developer</option>
				<option value="IT Project Coordinator"  {{request()->filter_category == 'IT Project Coordinator' ? 'selected' : ''}}>IT Project Coordinator</option>
				<option value="Technical Writer"  {{request()->filter_category == 'Technical Writer' ? 'selected' : ''}}>Technical Writer</option>
				<option value="Accounts Executive"  {{request()->filter_category == 'Accounts Executive' ? 'selected' : ''}}>Accounts Executive</option>
				<option value="HR Associate"  {{request()->filter_category == 'HR Associate' ? 'selected' : ''}}>HR Associate</option>
				<option value="HR Manager"  {{request()->filter_category == 'HR Manager' ? 'selected' : ''}}> HR Manager</option>
	            <option value="Fresher"  {{request()->filter_category == 'Fresher' ? 'selected' : ''}}>Fresher</option>
	        </select>
		</div>
		<div class="col-sm-5 col-md-5 m-t-xs">
			 <select class="chosen-select" id="filter_name" name="filter_name">
	            <option value="" {{request()->filter_name == '' ? 'selected' : ''}}>Candidate Name</option>
	            @foreach($names as $name)
	            <option value="{{$name->id}}" {{request()->filter_name == $name->id ? 'selected' : ''}}>{{$name->name}}</option>
	            @endforeach
	        </select>
		</div>
		<div class="col-md-2 text-right">

            </div>
	</div>
	<div class="row filter-row" style="padding: 0px 0px 10px 0px">
		<div class="col-sm-5 col-md-5 m-t-xs">
			 <select class="chosen-select" id="filter_status" name="filter_status">
	            <option value="" {{request()->filter_status == '' ? 'selected' : ''}}>Select Status</option>
	            <option value="Pending" {{request()->filter_status == 'Pending' ? 'selected' : ''}}>Pending</option>
	            <option value="Processing" {{request()->filter_status == 'Processing' ? 'selected' : ''}}>Processing</option>
	            <option value="Selected" {{request()->filter_status == 'Selected' ? 'selected' : ''}}>Selected</option>
	            <option value="Rejected" {{request()->filter_status == 'Rejected' ? 'selected' : ''}}>Rejected</option>
	            <option value="On Hold" {{request()->filter_status == 'On Hold' ? 'selected' : ''}}>On Hold</option>
	            <option value="Test Not Attended" {{request()->filter_status == 'Test Not Attended' ? 'selected' : ''}}>Test Not Attended</option>
	            <option value="Interview Not Attended" {{request()->filter_status == 'Interview Not Attended' ? 'selected' : ''}}>Interview Not Attended</option>
                <option value="Can be Considered" {{request()->filter_status == 'Can be Considered' ? 'selected' : ''}}>Can be Considered</option>
	            <option value="Declined Offer" {{request()->filter_status == 'Declined Offer' ? 'selected' : ''}}>Declined Offer</option>
	        </select>
		</div>
		<div class="col-sm-2 col-md-2 m-t-xs">
			<input class="form-control active p-xs datetimepicker" type="text" id="filter_applied_on" name="appliedOn"  value="{{request()->applied_on}}" placeholder = "Select Applied Date">
		</div>
		<div class="col-sm-3 col-md-3 m-t-xs">
			<input class="form-control active p-xs" type="text" id="daterange" name="daterange"  value="{{request()->daterange}}">
		</div>
		<div class="col-md-2 m-t-xs">
               <div class="form-group">
                    <button class="btn btn-info btn reset" >Reset</button>
                </div>
            </div>
	</div>
</form>
<!-- Search Filter -->
