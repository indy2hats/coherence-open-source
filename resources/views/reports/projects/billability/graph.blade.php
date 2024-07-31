@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-12">
        <strong><h2 class="page-title">Project Progress Graph</h3></strong>
     </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content animated fadeInUp">
                @include('reports.projects.billability.graph_search')
                <div class="ibox-content project-content" id="project_content" style="display:block;">
                    <div class="ibox-content billability-time-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
@include('reports.projects.billability.graph_script')
@endsection
