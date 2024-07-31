<!-- Search Filter -->

<div class="ibox-title" id="filter-section">

    <h5>Filter</h5>
    <div class="ibox-tools">
        <i class="fa fa-chevron-down"></i>
    </div>
    </div>
<div class="ibox-content mb10 filter-area">
    <form action="{{ route('taskSearch') }}" method="post" autocomplete="off" id="search-task">
        @csrf
        <div class="row filter-row pt-20 pb-20">
            <div class="col-sm-6 col-md-3">
                <select class="chosen-select" data-placeholder="Select project" id="search_project_name" name="search_project_name[]" multiple >
                    @if (!empty($searchedProject))
                    @foreach($searchedProject as $sproject)
                    <option value="{{$sproject->id}}" selected>{{$sproject->project_name}}</option>
                    @endforeach
                    @endif
                </select>
                <a href="#" class="clear-single-filter pull-right">clear</a>
            </div>
            <div class="col-sm-6 col-md-3">
                <select class="chosen-select" id="search_task_name" name="search_task_name">
                    @if (!empty($searchedTask))
                    <option value="{{$searchedTask->id}}" selected>{{$searchedTask->title}}</option>
                    @else
                    <option value="">Select Task</option>
                    @endif
                </select>
                <a href="#" class="clear-single-filter pull-right">clear</a>
            </div>
            
            <div class="col-sm-6 col-md-3">
                <select class="chosen-select" id="search_task_type" name="search_task_type">
                    <option value="all" selected {{request()->search_task_type == 'all' ? 'selected' : ''}}>All Task
                    </option>
                    <option value="upcomming" {{request()->search_task_type == 'upcomming' ? 'selected' : ''}}>
                        Upcoming
                        Task</option>
                    <option value="ongoing" {{request()->search_task_type == 'ongoing' ? 'selected' : ''}}>Task in
                        Progress</option>
                    <option value="completed" {{request()->search_task_type == 'completed' ? 'selected' : ''}}>
                        Completed
                        Task</option>
                    <option value="overdue" {{request()->search_task_type == 'overdue' ? 'selected' : ''}}>Overdue
                        Task
                    </option>
                </select>
            </div>
            <div class="col-sm-6 col-md-3">
                <select class="chosen-select" id="search_project_company" name="search_project_company">
                    <option value="">Select Client / Account</option>
                    @foreach ($clientsList as $client)
                    <option value="{{$client->id}}"
                        {{request()->search_project_company == $client->id ? 'selected' : ''}}>
                        {{$client->company_name}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <select class="chosen-select" id="task_status" name="task_status">
                    <option value="">Select Status</option>
                     @foreach($types as $type)
                    <option value="{{$type->title}}" {{request()->task_status == $type->title ? 'selected' : ''}}>
                        {{$type->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-md-3">
                <select class="chosen-select" id="assigned_to" name="assigned_to">
                    <option value="" {{request()->assigned_to == '' ? 'selected' : ''}}>Assigned To</option>
                    @foreach($users as $user)
                    <option value="{{$user->id}}" {{request()->assigned_to == $user->id ? 'selected' : ''}}>
                        {{$user->full_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-md-3">
                <select class="chosen-select" id="filter_tasks" name="filter">
                    <option value="">Sort By</option>
                    <option value="Created Date" {{request()->filter == 'Created Date' ? 'selected' : ''}}>Created
                        date
                    </option>
                    <option value="Deadline" {{request()->filter == 'Deadline' ? 'selected' : ''}}>Deadline</option>
                    <option value="Time Spent" {{request()->filter == 'Time Spent' ? 'selected' : ''}}>Time Spent
                    </option>
                </select>
            </div>
            <div class="col-sm-6 col-md-3">
                <a href="/tasks" class="btn btn-w-m btn-info" style="padding: 4px 12px;"> Clear Filters</a>
            </div>
        </div>
    </form>
</div>
<!-- Search Filter -->