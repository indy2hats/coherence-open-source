@extends('layout.main')
@section('content')
<!-- page title -->
<div class="row">
    <div class="col-md-7 pull-left">
        <strong><h2 class="page-title">Users</h3></strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success add-user create-modal" data-toggle="modal" data-target="#add_employee"><i class="ri-add-line"></i> Add User</a>                    
    </div>
</div>
<!-- /page title -->
<div class="row grid animated fadeInUp">
@include('users.grid')
</div>

@include('users.create')
@include('users.delete')

<div id="edit_employee" class="modal custom-modal fade" role="dialog">
    {{-- @include('users.edit') --}}
</div>

@endsection
@section('after_scripts')
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/resources/users/script-min.js') }}"></script>
@endsection