<div class="ibox-content">
   
        @csrf
    <div class="row">
        <div class="col-md-2">
            <input class="form-control active" type="text" id="daterange" name="daterange" placeholder="Select Date Range"  value="{{$date}}" autocomplete="off">
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <input class="form-control datepicker dateInput" type="text" name="date" id="date" placeholder="Select Month"  value="{{$dateMonth}}" autocomplete="off">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <select class="chosen-select" name="days" id="days">
                    <option value="">Working Days & Holidays</option>
               
                    <option value="non-workdays" {{ request()->days == 'non-workdays' ? 'selected': '' }}>Week Ends & Holidays</option>                 
                </select>
            </div>
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
                <select class="chosen-select" name="clientId" id="clientId">
                    <option value="">Select Client / Account</option>
                    @foreach ($clients as $client)
                    <option value="{{$client->id}}" {{ request()->clientId == $client->id ? 'selected': '' }}>{{$client->company_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                @php             
                    $userTypes =config('general.timesheets.user-type.labels');
                @endphp
                <select class="chosen-select" data-placeholder="Select User Type" name="userType" id="userType">                   
                    <option value=""></option> 
                    @foreach ($userTypes as $key => $type)
                    <option value="{{$key}}" {{ (string)$key== $userType ? 'selected': '' }}>{{$type}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <select class="chosen-select" data-placeholder="Select User" name="userId[]" id="userId" multiple>
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                    <option value="{{$user->id}}" {{ (in_array($user->id, $userId)) ? 'selected': '' }}>{{$user->full_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <select class="chosen-select" data-placeholder="Select Session Type" name="sessionType[]" id="sessionType" multiple>                   

                    @foreach ($sessionTypes as $key => $type)
                    <option value="{{$key}}" {{ (in_array($key, $sessionType)) ? 'selected': '' }}>{{$type}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <select class="chosen-select" data-placeholder="Select Project Category" id="projectCategory" name="projectCategory">
                    <option value="" selected>Select Project Category</option>
                    @foreach(config('export.projectCategories') as $categoryValue => $label)
                        <option value="{{ $categoryValue }}" {{ request('projectCategory') == $categoryValue ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 filter-button-group">
            <div class="form-group">
                <button class="btn btn-primary btn reset">Reset</button>
                <button href="#" class="btn btn-w-m btn-success" id="export-timesheet-csv"><i class="ri-download-line"></i> Export Data </button>
            </div>
        </div>
    </div>
    
</div>