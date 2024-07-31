@extends('layout.main')
@section('content')
<div class="main">
   @include('employeetasks.show')
</div>

<!-- /Project files Modal -->
@include('employeetasks.create')
@include('employeetasks.delete')
@include('employeetasks.projectfiles')
@include('employeetasks.projectcredentials')
<div class="modal custom-modal fade" id="edit_task_session" role="dialog">
</div>
@stop
@section('after_scripts')
<script src="{{ asset('js/resources/employeetasks/script-min.js') }}"></script>
@endsection