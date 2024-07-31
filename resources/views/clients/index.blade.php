@extends('layout.main')
@section('content')
<!-- page title -->
<div class="row">
    <div class="col-md-7 pull-left">
        <strong><h2 class="page-title">Client</h3></strong>
    </div>
    <div class="col-md-5 text-right ml-auto m-b-30">
        <a href="#" class="btn btn-w-m btn-success" data-toggle="modal" data-target="#add_client"><i class="ri-add-line"></i> Add Client</a>                    
    </div>
</div>

<!-- /page title -->
<div class="row client-grid-row animated fadeInUp">
 @include('clients.grid') 
</div>
@include('clients.create')
@include('clients.delete') 

</div>
<!-- Edit Client Modal -->
<div id="edit_client" class="modal custom-modal fade" role="dialog">
    {{-- @include('clients.edit') --}}
</div>

<!-- /Edit Client Modal -->
@endsection
@section('after_scripts')
       <script src="{{ asset('js/resources/clients/script-min.js') }}"></script>
@endsection