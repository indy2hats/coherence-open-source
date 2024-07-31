@extends('layout.main')

@section('content')

<div class="row">
    <div class="col-md-7">
        <strong>
            <h2 class="page-title m-b">Manage Easy Access</h2>
        </strong>
    </div>

    <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add_easy_access"><i class="ri-add-line"></i> Add New</a>
    </div>
</div>

<div class="list animated ibox-content panel fadeInUp">
	@include('easy-access.list')
</div>

<div id="edit_item" class="modal custom-modal fade" role="dialog">
	@include('easy-access.edit')
</div>
@include('easy-access.create')
@include('easy-access.delete')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/easy-access/script-min.js') }}"></script>
@endsection
