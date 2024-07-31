@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">QA Feedbacks</h3>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        @can('manage-task')
        <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#create_feedback"><i class="ri-add-line"></i> Add Feedback</a>
        @endcan
    </div>
</div>
<div class="list animated fadeInUp">
    <div class="row">
    <div class="col-md-12">
    @include('qa-feedback.search')
    <div class="ibox-content issues">
	@include('qa-feedback.list')
    </div>
</div>
</div>
</div>
@include('qa-feedback.create-feedback')
@include('qa-feedback.delete-feedback')

@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/qa-feedback/script-min.js') }}"></script>
@endsection