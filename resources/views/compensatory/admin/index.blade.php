@extends('layout.main')
@section('content')

<div class="main">
@include('compensatory.admin.list')    
</div>

@include('compensatory.admin.accept')
@include('compensatory.admin.reject')
@include('compensatory.admin.delete')
<div class="modal custom-modal fade" id="edit_application" role="dialog">
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/compensatory/admin/script-min.js') }}"></script>
@endsection