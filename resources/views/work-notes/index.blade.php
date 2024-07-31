@extends('layout.main')
@section('content')
    <div class="row">
        <div class="col-md-7 pull-left">
            <strong>
                <h2 class="page-title">Work Notes</h3>
            </strong>
        </div>
        <div class="col-md-5 text-right ml-auto m-b-30">
            <button class="btn-success btn add-work-note"><i class="ri-add-line"></i> Add Work Note</button>
        </div>
    </div>
    <div class="row animated fadeInUp" id="notes" style="margin-top: 10px; margin-bottom: 30px;">
        @include('work-notes.list')
    </div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/work-notes/script-min.js') }}"></script>
@endsection