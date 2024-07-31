@extends('layout.main')

@section('content')

<div class="row">
    <div class="col-md-7 pull-left">
        <strong>
            <h2 class="page-title">Manage Checklists</h3>
        </strong>
    </div>
     <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success add_item_category" data-toggle="modal" data-target="#add_item_category"><i class="ri-add-line"></i> Add Category</a>
        @can('manage-leave')<a href="/employee-checklist" class="btn btn-w-m btn-success"><i class="ri-eye-line"></i> Employees Checklist</a> @endcan                  
    </div>
</div>

<div class="list animated fadeInUp row checklist-group" style="display: flex; flex-wrap: wrap;">
	@include('checklists.manage-checklist.view')
</div>

@include('checklists.manage-checklist.share-with')
@include('checklists.manage-checklist.create')
@include('checklists.manage-checklist.create-category')
@include('checklists.manage-checklist.delete')
@include('checklists.manage-checklist.edit')
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/checklists/manage-checklist/script-min.js') }}"></script>
@endsection