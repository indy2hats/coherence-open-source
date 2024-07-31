@extends('layout.main')

@section('content')
@include('assets.show')
@include('assets.add-documents')
@include('assets.doc-delete')
@endsection
@section('after_scripts')
<link href="{{ asset('css/plugins/c3/c3.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/plugins/c3/c3.min.js') }}"></script>
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('js/resources/assets/script-min.js') }}"></script>
<link href="{{ asset('css/plugins/dropzone/basic.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/dropzone/dropzone.css') }}" rel="stylesheet">
<script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>

<script>
    Dropzone.options.dropzoneForm = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 5, // MB
        dictDefaultMessage: "<strong>Drop files here or click to upload. </strong></br> (This is just a demo dropzone. Selected files are not actually uploaded.)",
        acceptedFiles: ".jpg, .jpeg, .png, .pdf",
    };

    $(document).on('click','.add-asset-documents', function (e) {
      $('#dropzoneForm #upload_asset_id').val($('#asset-id').attr('data-id'));
    });

    $('#add_asset_documents').on('hidden.bs.modal', function() {
       getAssetDocuments();
    });

    $('#delete_asset_document').on('hidden.bs.modal', function() {
       getAssetDocuments();
    });

  </script>
@endsection