<form action="{{route('projectBillabilityHoursGraph')}}" method="post" id="project-progress-graph-form">
   @csrf
   <div class="row">
        <div class="col-md-3">
            <input class="form-control active weekpicker" type="text" name="dateRange" id="dateRange" placeholder="Select week"/>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select class="chosen-select " name="dataDisplayType" id="dataDisplayType" data-placeholder="Select Data Display Type" data-disable-search="true">
                    <option value=""></option>
                    <option value="year" {{ $selectedDataDisplayType == 'year' ? 'selected' : '' }}>Year</option>
                    <option value="month" {{ $selectedDataDisplayType == 'month' ? 'selected' : '' }}>Month</option>
                    <option value="week" {{ $selectedDataDisplayType == 'week' ? 'selected' : '' }}>Week</option>
                    <option value="day" {{ $selectedDataDisplayType == 'day' ? 'selected' : '' }}>Day</option>
                </select>
                <span class="text-danger hide small" id="dataDisplayType-error">Please Select The Display Type</span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <select class="chosen-select project-filter" name="projectId" id="projectId" data-placeholder="Select Projects">
                    <option value="" ></option>
                    @foreach($projectList as $key => $item)
                        <option value="{{ $item->id }}" {{ $projectId == $item->id ? 'selected': '' }}>{{ $item->project_name }}</option>
                    @endforeach
                </select>
                <span class="text-danger hide small" id="project-error">Please Select The Project</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="billability-report-btn-group">
                <div class="form-group">
                    <button class="btn btn-primary btn search">Search</button>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn reset-filter">Clear Filter</button>
                </div>
            </div>
        </div>
    </div>
</form>
