<div class="ibox-title">    
    <div class="row">
        <div class="col-md-3">
        </div>
        <form id="task-search-form">
            <div class="col-md-2">
                <input class="form-control active" type="text" id="daterange" name="daterange"  value="{{$date}}">
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
            <div class="col-md-2">
                <div class="form-group">
                    <select class="chosen-select" name="projectId" id="projectId">
                        <option value="">Select Project</option>
                        @foreach ($projects as $project)
                        <option value="{{$project->id}}" {{ request()->project == $project->id ? 'selected': '' }}>{{$project->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select class="chosen-select" name="severity" id="severity">
                        <option value="" selected>Select Severity</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <button class="btn btn-primary btn search" >Search</button>
                </div>
            </div>
        </form>
    </div>
</div>