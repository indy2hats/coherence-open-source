@extends('layout.main')

@section('content')
<!-- page title -->
<div class="row">
	<div class="col-md-7 pull-left">
		<strong>
			<h2 class="page-title">Projects</h3>
		</strong>
	</div>
	<div class="col-md-5 text-right ml-auto m-b-30">
	@can('manage-projects')
		<a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_project"><i class="ri-add-line"></i> Add Project</a>
	@endcan
	@if(Auth::user()->hasRole('client'))
		<a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#add_task"><i class="ri-add-line"></i> Add Task</a>
	@endif
	</div>
</div>
<!-- /page title -->
<div class="content-div animated fadeInUp">
	@include('projects.search')
	<div class="main panel ibox-content">
		@include('projects.list')
	</div>
</div>
@include('projects.create')
@include('projects.delete')
@if(Auth::user()->hasRole('client'))
	@include('tasks.add')
@endif
<div id="edit_project" class="modal custom-modal fade" role="dialog">
</div>
@endsection

@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/projects/script-min.js') }}"></script>
@endsection