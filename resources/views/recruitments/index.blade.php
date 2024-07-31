@extends('layout.main')
@section('content')
    <div class="row">
        <div class="col-md-7 pull-left">
            <strong>
                <h2 class="page-title">Recruitments</h3>
            </strong>
        </div>
        
        <div class="col-md-5 text-right ml-auto m-b-30">
           
		    <a href="#" class="btn btn-w-m btn-success create-modal" data-toggle="modal" data-target="#create_candidate"><i class="ri-add-line"></i> Add New</a>

                    
        </div>
    </div>
    
    <div class="content-div animated fadeInUp" id="list">
        @include('recruitments.view')
    </div>

    @include('recruitments.create')
    @include('recruitments.delete')
    <div id="edit_candidate" class="modal custom-modal fade" role="dialog">
    </div>
    <div class="modal custom-modal fade" id="new_schedule" role="dialog">
        {{-- @include('recruitments.new-schedule') --}}
    </div>
    
@endsection
@section('after_scripts')
<link href="{{ asset('css/plugins/footable/footable.core.css')}}" rel="stylesheet">
<script src="{{ asset('js/plugins/footable/footable.all.min.js')}}"></script>
<link href="{{ asset('css/plugins/clockpicker/clockpicker.css')}}" rel="stylesheet">
<script src="{{ asset('js/plugins/clockpicker/clockpicker.js')}}"></script>
<script src="{{ asset('js/resources/recruitments/script-min.js') }}"></script>
@endsection