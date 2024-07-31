<h2><strong>January, 2020</strong></h2>
<div class="row" id="search-head" data-start-date="">
    <div class="col-md-8">
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <select class="chosen-select" name="projectId" id="projectId">
                <option value="">Select Project</option>
                @foreach ($projects as $project)
                <option value="{{$project->id}}" {{ request()->projectId == $project->id ? 'selected': '' }}>{{$project->project_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <select class="chosen-select" name="userId" id="userId">
                <option value="">Select User</option>
                @foreach ($users as $user)
                <option value="{{$user->id}}" {{ request()->userId == $user->id ? 'selected': '' }}>{{$user->full_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>