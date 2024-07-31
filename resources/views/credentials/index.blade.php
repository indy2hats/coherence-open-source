@extends('layout.main')
@section('content')
<div class="row">
        <div class="col-md-7 pull-left">
            <strong>
                <h2 class="page-title">Credentials</h3>
            </strong>
        </div>
        <div class="col-md-5 text-right ml-auto m-b-30">
            <button class="btn-success btn" data-toggle="modal" data-target="#create_credential"><i class="ri-add-line"></i> Add Credential</button>
        </div>
    </div>
<div class="row animated fadeInUp" id="table" style="margin-top: 10px; margin-bottom: 30px;">
    @include('credentials.list')
</div>
<div id="edit_credential" class="modal custom-modal animated fadeInUp" role="dialog"></div>
@include('credentials.create')
@include('credentials.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/credentials/script-min.js') }}"></script>
@endsection