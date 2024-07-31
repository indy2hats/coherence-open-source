@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-8">
        <strong>
            <h2 class="page-title">Upcoming Tasks</h3>
        </strong>
    </div>
</div>
<div class="content-div animated fadeInUp">
    <div class="ibox-content mb10 panel filter-area">
        <div class="row filter-row pt-20 pb-20">
            <div class="col-sm-6 col-md-3">
                <label>Project Name</label>
                <select class="chosen-select" id="project"
                    name="project">
                    <option value="">Select Project</option>
                    @foreach ($projects as $project)
                    <option value="{{$project->id}}">{{$project->project_name}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="ibox-content panel">        
        <table id="upcoming-dt" class="table table-hover dataTables-example" style="width:100%">
            <thead>
                <tr>
                    <th> </th>
                    <th>Task Code</th>
                    <th>Task</th>
                    <th>Jira ID</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- /Table -->
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/employeetasks/dataTable-script-min.js') }}"></script>
<script src="{{ asset('js/resources/employeetasks/script.min.js') }}"></script>
@endsection