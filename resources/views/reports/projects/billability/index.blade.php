@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-md-12">
        <strong><h2 class="page-title">Project Billability Report</h3></strong>
     </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content animated fadeInUp">
                @include('reports.projects.billability.search')
                <div class="ibox-content project-content" id="project_content" style="display:block;">
                    @include('reports.projects.billability.sheet')
                </div>

                <div class="ibox-content billability-chart" style="display:none;">
                    <div class="ibox-content billability-time-chart"></div>
                    <div class="ibox-content billability-percentage-chart"></div>
                </div>

            </div>
        </div>
    </div>
</div>
 @include('reports.projects.billability.save_filter')
 @include('reports.projects.billability.confirm_filter_deletion')
@endsection
@section('after_scripts')
@include('reports.projects.billability.script')
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
@endsection
