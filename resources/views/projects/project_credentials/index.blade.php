@extends('layout.main')

@section('content')

<!-- Page Title -->
<div class="row">
    <!-- <div class="col-md-2"></div> -->
    <div class="col-md-8">
        <strong>
            <h3 class="page-title" style="font-size: 25px" data-id="{{$id}}" id="project_info">{{$project->project_name}}</h3>
        </strong>
    </div>
    @can('manage-project-credentials')
        <div class="col-md-4 text-right">
            <button class="btn-success btn" data-toggle="modal" data-target="#create_credential"><i class="ri-add-line"></i> Add Credential</button>
        </div>
    @endcan
    <!-- <div class="col-md-2"></div> -->
</div>
<!-- /Page Title -->
<div id="edit_credential" class="modal custom-modal animated fadeInUp" role="dialog"></div>
<div class="row pt20" id="table">
    @include('projects.project_credentials.list')
</div>
@include('projects.project_credentials.create')
@include('projects.project_credentials.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/projects/project_credentials/script-min.js') }}"></script>
@endsection