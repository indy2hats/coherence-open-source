@extends('layout.main')

@section('content')
<!-- page title -->
<div class="row">
	<div class="col-md-7 pull-left">
		<strong>
			<h2 class="page-title">Salary Hike</h3>
		</strong>
	</div>
	<div class="col-md-5 text-right ml-auto m-b-30">
	@can('manage-salary-hike')
		<a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_salary_hike">Add Salary Hike</a>
	@endcan
	</div>
</div>
<div class="content-div animated fadeInUp">
	@include('salary-hike.search')
	<div class="main panel ibox-content">
		@include('salary-hike.list')
	</div>
</div>
@include('salary-hike.create')
<div id="edit_asset" class="modal custom-modal fade" role="dialog" tabindex="-1">
</div>
@endsection

@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/salary-hike/script-min.js') }}"></script>
@endsection