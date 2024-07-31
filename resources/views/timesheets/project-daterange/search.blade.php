<div class="ibox-content">
    <div class="row">
        <div class="col-md-5">
        </div>
        <div class="col-md-2">
            <input class="form-control active" type="text" id="daterange" name="daterange"  value="{{$date}}">
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
        <div class="col-md-1">
            <div class="form-group">
                <button class="btn btn-primary btn search" >Search</button>
            </div>
        </div>
    </div>
</div>