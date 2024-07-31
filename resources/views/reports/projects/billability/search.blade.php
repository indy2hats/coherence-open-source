
<form action="{{route('projectBillabilityReport')}}" method="post" id="project-billibility-form">
   @csrf
   <div class="row">
        <div class="col-md-3">
            <input class="form-control active report-filter" type="text" id="daterange" name="daterange">
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select class="chosen-select client-filter" name="client[]" id="client" multiple data-placeholder="Select Clients">
                    <option value="" ></option>
                    @foreach($billableClients as $key => $item)
                        <option value="{{$item->id}}" {{ (in_array($item->id, $selectedClients)) ? 'selected': '' }}>{{$item->company_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select class="chosen-select project-filter" name="project[]" id="project" multiple data-placeholder="Select Projects">
                    <option value="" ></option>
                    @foreach($projectList as $key => $item)
                        <option value="{{$item->id}}" {{ (in_array($item->id, $project)) ? 'selected': '' }}>{{$item->project_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select class="chosen-select session-filter" name="sessionType[]" id="sessionType" multiple  data-placeholder="Select Session Types">
                    <option value=""></option>
                    @foreach($sessionTypes as $key => $type)
                        <option value="{{$key}}" {{ (in_array($key, $sessionType)) ? 'selected': '' }}>{{$type}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row justify-content-end">
        <div class="col-md-3">
            <div class="billability-report-btn-group-left">
                <div class="form-group" style="width: 150px; min-width: 200px;">
                    <select class="chosen-select saved-filter" name="savedFilter" id="savedFilter" data-placeholder="Select Saved Filters">
                        <option value=""></option>
                        @foreach($savedFilters as $savedFilter)
                        <option value="{{ $savedFilter->id }}" {{ ($savedFilter->id == $selectedSavedFilter) ? 'selected': '' }} >{{ $savedFilter->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button data-toggle="modal" data-target="#save-selected-filters" class="btn btn-primary btn-success save-filter">Save Filter</button>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-3">
            <div class="billability-report-btn-group">
                <div class="form-group">
                    <button class="btn btn-primary btn search">Search</button>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn reset-filter">Clear Filter</button>
                </div>
                <div class="form-group ">
                    <input type='hidden' name='page-type' id='page-type' value='list'>
                    <button class="btn btn-info btn billability-graph button-graph">Show Graph</button>
                </div>
            </div>
        </div>
    </div>
</form>
