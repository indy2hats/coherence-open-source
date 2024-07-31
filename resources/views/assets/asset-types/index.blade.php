@extends('layout.main')

@section('content')
<!-- page title -->
<div class="row">
	<div class="col-md-7 pull-left">
		<strong>
			<h2 class="page-title">Types</h3>
		</strong>
	</div>
	<div class="col-md-5 text-right ml-auto m-b-30">
	@can('manage-assets')
		<a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_asset_type"><i class="ri-add-line"></i> Add Types</a>
		<a target="_blank" href="/attributes" class="btn btn-w-m btn-success"><i class="ri-add-line"></i> Manage Attributes</a>
	@endcan
	</div>
</div>
<div class="content-div animated fadeInUp">
	<div class="main panel ibox-content">
		@include('assets.asset-types.list')
	</div>
</div>
@include('assets.asset-types.create')
@include('assets.asset-types.delete')
<div id="edit_asset_type" class="modal custom-modal fade" role="dialog" tabindex="-1">
</div>
@endsection

@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/assets/asset-types/script-min.js') }}"></script>
@endsection