@extends('layout.main')

@section('content')
<!-- page title -->
<div class="row">
        <div class="col-md-7 pull-left">
                <strong>
                        <h2 class="page-title">Attributes</h3>
                </strong>
        </div>
        <div class="col-md-5 text-right ml-auto m-b-30">
        @can('manage-assets')
                <!-- <a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_attributes"><i class="ri-add-line"></i> Add Attributes</a> -->
                <a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#add_attributes"><i class="ri-add-line"></i> Add Attributes</a>
        @endcan
        </div>
</div>
<div class="content-div animated fadeInUp">
        <div class="main panel ibox-content">
                @include('assets.attributes.list')
        </div>
</div>
@include('assets.attributes.add')
@include('assets.attributes.create')
@include('assets.attributes.delete')

<div id="edit_attribute" class="modal custom-modal fade" role="dialog" tabindex="-1">
</div>
@endsection

@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>;
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/assets/attributes/script-min.js') }}"></script>
@endsection