<!-- Search Filter -->
<div class="ibox-content panel mb10">
	<form action="{{route('archivedProjectSearch')}}" method="post" autocomplete="off" id="search-project">
	@csrf
	<div class="row filter-row" style="padding-top: 10px;padding-bottom: 10px">
		<div class="col-sm-6 col-md-3">
			<select class="chosen-select" id="search_project_name" name="search_project_name">
				<option value="">Select Project</option>
				@foreach ($searchs as $project)
				<option value="{{$project->id}}" {{request()->search_project_name == $project->id ? 'selected' : ''}}>{{$project->project_name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-6 col-md-3">
			<select class="chosen-select" id="search_project_company" name="search_project_company">
				<option value="">Select Client / Account</option>
				@foreach ($clientsList as $client)
				<option value="{{$client->id}}" {{request()->search_project_company == $client->id ? 'selected' : ''}}>{{$client->company_name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-6 col-md-3">
			<select class="chosen-select" id="search_project_priority" name="projectPriority">
				<option value="">Select Priority</option>
				<option value="High" {{request()->projectPriority == 'High' ? 'selected' : ''}}>High</option>
				<option value="Medium" {{request()->projectPriority == 'Medium' ? 'selected' : ''}}>Medium</option>
				<option value="Low" {{request()->projectPriority == 'Low' ? 'selected' : ''}}>Low</option>
			</select>
		</div>
		<div class="col-sm-6 col-md-3">
			<select class="chosen-select" id="projectCategory" name="projectCategory">
				<option value="" {{request()->projectCategory == '' ? 'selected' : ''}}>Select Category</option>
				<option value="External" {{request()->projectCategory == 'External' ? 'selected' : ''}}>External</option>
				<option value="Internal" {{request()->projectCategory == 'Internal' ? 'selected' : ''}}>Internal</option>
				<option value="Upskilling" {{request()->projectCategory == 'Upskilling' ? 'selected' : ''}}>Upskilling</option>
			</select>
		</div>
	</div>
	</form>
</div>
<!-- Search Filter -->