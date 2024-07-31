@extends('layout.main')

@section('content')
<div class="row m-b">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">Manage Session Type</h2>
        </strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add_session_type"><i class="ri-add-line"></i> Add Session Type</a>
    </div>
</div>
<div class="list animated fadeInUp">
	<div class="ibox-content panel session-list">
    @include('settings.session-types.list')
    </div>
</div>
<div id="edit_type" class="modal custom-modal fade" role="dialog">
</div>
@include('settings.session-types.create')
@include('settings.session-types.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/settings/session-types/script-min.js') }}"></script>
@endsection