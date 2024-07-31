@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">Issue Records</h3>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add_issue"><i class="ri-add-line"></i> Add Issue</a>
        <a href="#" class="btn btn-w-m btn-info" data-toggle="modal" data-target="#add_category"><i class="ri-add-line"></i> Add Category</a>
    </div>
</div>
<div class="list animated fadeInUp">
	@include('issue-records.managesheet')
</div>
@include('issue-records.create')
@include('issue-records.delete')

<div id="edit_issue" class="modal custom-modal fade" role="dialog">
</div>
@include('issue-records.create-category')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/issue-records/script-min.js') }}"></script>
@endsection