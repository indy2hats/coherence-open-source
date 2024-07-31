<div class="ibox-title">    
    <div class="row">
       
        <form id="issue-search-form">
            <div class="col-md-2">
                
                <input class="form-control datetimepicker" id="fromdate" type="text" name="from_date" value="{{$fromDate}}" >
            </div>
            <div class="col-md-2">
                
                <input class="form-control datetimepicker" id="todate" type="text" name="to_date" value="{{$toDate}}" >
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select class="chosen-select" name="added_by" id="added_by">
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                        <option value="{{$user->id}}" {{ request()->userId == $user->id ? 'selected': '' }}>{{$user->full_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select class="chosen-select" name="project_id" id="project_id">
                        <option value="">Select Project</option>
                        @foreach ($projects as $project)
                        <option value="{{$project->id}}" {{ request()->project == $project->id ? 'selected': '' }}>{{$project->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select class="chosen-select" name="category" id="category">
                        <option value="" selected>Select Category</option>
                        @foreach ($categories as $category)
                        <option value="{{$category->slug}}">{{$category->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <button class="btn btn-primary btn search">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>