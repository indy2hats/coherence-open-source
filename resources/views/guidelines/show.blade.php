@extends('layout.main')
@section('content')
<div class="list">
  @include('guidelines.details')  
</div>
@include('guidelines.delete') 
<div id="edit_guideline" class="modal custom-modal fade" role="dialog">
    </div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/guidelines/script-show-min.js') }}"></script>
@endsection