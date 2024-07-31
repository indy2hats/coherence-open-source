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
    @can('manage-project-documents')
        <div class="col-md-4 text-right">
            <button class="btn-info btn" data-toggle="modal" data-target="#upload_link">Add Link</button>
            <button class="btn-success btn" data-toggle="modal" data-target="#upload_file">Upload File</button>
        </div>
    @endcan
</div>
<!-- /Page Title -->
<div class="row pt20" id="table">
    @include('projects.project_files.files-list')
</div>
@include('projects.project_files.upload_file')
@include('projects.project_files.upload_link')
@include('projects.project_files.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/projects/project_files/script-min.js') }}"></script>
@endsection