@extends('layout.main')

@section('content')
<!-- page title -->
<div class="row">
	<div class="col-md-7 pull-left">
		<strong>
			<h2 class="page-title">Assets</h3>
		</strong>
	</div>
	<div class="col-md-5 text-right ml-auto m-b-30">
	@can('manage-assets')
		<a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_asset_type">Add Type</a>
		<a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_asset_vendor"> Add Vendor</a>
		<a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_asset"> Add Asset</a>
	@endcan
	</div>
</div>
<div class="content-div animated fadeInUp">
	@include('assets.search')
	<div class="main panel ibox-content">
		@include('assets.list')
	</div>
</div>
@include('assets.create')
@include('assets.assign')
@include('assets.delete')
@include('assets.doc-delete')
@include('assets.return')
@include('assets.ticket-raise')
@include('assets.asset-vendors.create')
@include('assets.asset-types.create')
<div id="edit_asset" class="modal custom-modal fade" role="dialog" tabindex="-1">
</div>
@endsection

@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/assets/asset-vendors/script-min.js') }}"></script>
<script src="{{ asset('js/resources/assets/asset-types/script-min.js') }}"></script>
<script src="{{ asset('js/resources/assets/script-min.js') }}"></script>
@endsection