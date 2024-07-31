@extends('layout.main')

@section('content')
<div class="row">
    <div class="col-md-5">
        <strong>
            <h2 class="page-title">Manage Santas</h3>
        </strong>
    </div>
    <div class="col-md-7 text-right float-right ml-auto m-b-30">
         <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add_session_type"><i class="ri-add-line"></i> Add Santa</a>
        @hasrole('hr-manager')
        @if ($count != 0)
            <a href="{{ route('santa-members.setSanta') }}" class="btn btn-w-m btn-success">Set Santa</a> 
        @else
            <a href="{{ route('santa-members.viewSanta') }}" class="btn btn-w-m btn-success" > View Santa</a>
            <a href="{{ route('santa-members.resetSanta') }}" class="btn btn-w-m btn-success" > Reset Santa</a>
        @endif
        @endhasrole
    </div>
</div>
<div class="list animated fadeInUp">
	<div class="ibox-content panel session-list">
    @include('settings.santas.list')
    </div>
</div>
<div id="edit_type" class="modal custom-modal fade" role="dialog">
</div>
@include('settings.santas.create')
@include('settings.santas.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/settings/santas/script-min.js') }}"></script>
@endsection
