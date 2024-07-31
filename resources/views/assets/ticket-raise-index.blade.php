@extends('layout.main')

@section('content')
<!-- page title -->
<div class="row">
	<div class="col-md-7 pull-left">
		<strong>
			<h2 class="page-title">Tickets</h3>
		</strong>
	</div>
</div>
<!-- /page title -->
<div class="content-div animated fadeInUp">
	@include('assets.ticket-search') 
	<div class="main panel ibox-content">
		@include('assets.ticket-raised-list')
	</div>
</div>
<div id="ticket_status_edit" class="modal custom-modal fade" role="dialog" tabindex="-1">
</div>
<div id="ticket_raise_edit_asset" class="modal custom-modal fade" role="dialog" tabindex="-1">
</div>
@endsection

@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/assets/script-min.js') }}"></script>
@endsection