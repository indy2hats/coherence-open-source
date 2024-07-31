@extends('layout.main')
@section('style')
<style>
    /* credentials page */

    input.single-input-field {
        background-color: #FFFFFF;
        background-image: none;
        border: 1px solid #e5e6e7;
        border-radius: 1px;
        color: inherit;
        display: inline-block;
        padding: 3px 12px;
        transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    }

    button.copyText {
        background-color: #FFFFFF;
        background-image: none;
        border: 1px solid #e5e6e7;
        border-radius: 1px;
        color: inherit;
        padding: 3px 8px;
        transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    }

    input.single-input-field:focus-visible {
        outline: 0;
        border: 1px solid #1ab394;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title m-b">Credentials</h2>
        </strong>
    </div>
    @can('manage-project-credentials')
    <div class="col-md-5 text-right ml-auto m-b-30">
        <button class="btn-success btn" data-toggle="modal" data-target="#create_credential"><i class="ri-add-line"></i> Add Credential</button>
    </div>
    @endcan
</div>
<div class="ibox-content panel mb10 filter-area">
    <form action="{{ route('taskSearch') }}" method="post" autocomplete="off" id="search-task">
        @csrf
        <div class="form-group form-focus select-focus focused">
            <label>Select Project<span class="required-label">*</span></label>
            <select class="chosen-select select_project_name" name="project_id" id="list_project_id">
                <option value="">Select Project</option>
                @foreach ($projects as $project)
                <option value="{{$project->id}}">{{$project->project_name}}
                </option>
                @endforeach
            </select>
            <div class="text-danger text-left field-error" id="label_project_id"></div>
        </div>
        @can('manage-project-credentials')
        <div class="form-group form-focus select-focus focused">
            <label>Select User<span class="required-label">*</span></label>
            <select class="chosen-select select_user_id" name="user_id" id="list_user_id">
                <option value="">Select User</option>
                @foreach ($users as $user)
                <option value="{{$user->id}}">{{$user->full_name}}
                </option>
                @endforeach
            </select>
            <div class="text-danger text-left field-error" id="label_user_id"></div>
        </div>
        @endcan
    </form>
</div>
<div class="row animated fadeInUp list" id="table" style="margin-top: 10px; margin-bottom: 30px;">

</div>
<div id="share_credential" class="modal custom-modal animated fadeInUp" role="dialog"></div>
<div id="edit_credential" class="modal custom-modal animated fadeInUp" role="dialog"></div>
@include('new-credentials.create')
@include('new-credentials.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/credentials/new-credentials/script.js') }}"></script>
<!-- <script src="{{ asset('js/resources/tasks/script-min.js') }}"></script> -->
@endsection