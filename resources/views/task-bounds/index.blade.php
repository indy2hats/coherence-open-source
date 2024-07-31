@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">Task Bounce List</h3>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <!-- <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add_client"><i class="ri-add-line"></i> Add Leave</a> -->
    </div>
</div>
<div class="list animated fadeInUp">
	@include('task-bounds.managesheet')
</div>

@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/task-bounds/script-min.js') }}"></script>
@endsection