<h2 class="page-title">{{$days['start'] ." - ".$days['end']}}</h3>
<div class="ibox-content">
    <div class="row" id="search-head" data-start-date="{{$days['day1_date']}}">
        <div class="col-md-7">
            <button type="button" class="btn btn-outline btn-link arrow-back" data-date="{{$days['day1_date']}}"><i class="ri-arrow-left-double-line ri-2x"></i></button>


            <button type="button" class="btn btn-outline btn-link todayBtn"><strong><i class="ri-calendar-2-line"></i> This Week</strong></button>


            <button type="button" class="btn btn-outline btn-link arrow-front" data-date="{{$days['day1_date']}}"><i class="ri-arrow-right-double-line ri-2x"></i></button>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <input class="form-control datepicker dateInput" type="text" name="date" id="date" value="{{$days['day1_date']}}" />
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
        <div class="col-md-1">
            <div class="form-group">
                <button class="btn btn-primary btn searchSheet" >Search</button>
            </div>
        </div>
    </div>
</div>