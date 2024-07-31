@extends('layout.main')
@section('content')
    <div class="row">
        <div class="col-md-7 pull-left">
            <strong>
                <h2 class="page-title m-b">Guidelines</h2>
            </strong>
        </div>
        <div class="col-md-5 text-right ml-auto m-b-30">
		    <a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_guideline"><i class="ri-add-line"></i> Add New</a>
        </div>
    </div>

        <div class="row search">
            @include('guidelines.search')
        </div>
    
    <div class="content-div animated fadeInUp" id="list">
            @include('guidelines.list')
    </div>

    @include('guidelines.create')
    @include('guidelines.delete')
    <div id="edit_guideline" class="modal custom-modal fade" role="dialog">
    </div>
    @include('guidelines.create-tag')
@endsection
@section('after_scripts')
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/resources/guidelines/script-min.js') }}"></script>
@endsection