@extends('layout.main')
@section('content')
<!-- page title -->
<div class="row">
    <div class="col-md-7 pull-left">
        <strong><h2 class="page-title">Newsletters</h3></strong>
    </div>
    @can('manage-newsletters')
	    <div class="col-md-5 text-right ml-auto m-b-30">
	        <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add_client"><i class="ri-add-line"></i> Add Newsletter</a>                    
	    </div>
	 @endcan
</div>

<!-- /page title -->
<div class="row grid-row animated fadeInUp">
 @include('newsletters.grid') 
</div>
@include('newsletters.create')

<!-- Edit Client Modal -->
<div id="edit_client" class="modal custom-modal fade" role="dialog">
    
</div>

<!-- /Edit Client Modal -->
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/newsletters/script-min.js') }}"></script>
@endsection