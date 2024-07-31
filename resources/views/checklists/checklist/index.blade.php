@extends('layout.main')

@section('content')

<div class="row">
    <div class="col-md-12">
        <strong>
            <h2 class="page-title">Checklists</h3>
        </strong>
    </div>
</div>

<div class="row list animated fadeInUp checklist-group">
	@include('checklists.checklist.view')
</div>
@include('checklists.checklist.save')
@endsection
@section('after_scripts')

<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">

<script src="{{ asset('js/resources/checklists/checklist/script-min.js') }}"></script>
@endsection
