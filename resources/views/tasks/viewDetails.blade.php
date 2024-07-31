@extends('layout.main')

@section('content')
<div class="main">
    <input type="hidden" class="userIdCookie" value={{auth()->user()->id}}>
@include('tasks.show')
</div>

@stop
@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{ asset('js/plugins/ionRangeSlider/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('js/plugins/clockpicker/clockpicker.js') }}"></script>
<link href="{{ asset('css/plugins/ionRangeSlider/ion.rangeSlider.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css') }}" rel="stylesheet">
<script src="{{ asset('js/plugins/typehead/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('js/resources/tasks/view-script-min.js') }}"></script>
<link href="{{ asset('css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/codemirror/codemirror.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/dropzone/basic.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/dropzone/dropzone.css') }}" rel="stylesheet">
<script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('js/plugins/codemirror/codemirror.js') }}"></script>
<script src="{{ asset('js/plugins/codemirror/mode/xml/xml.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
        $(document).on('change','#task_status', function (e) {
          @if((auth()->user()->cannot('manage-tasks') & $task->users->contains('id', auth()->user()->id)) || $task->users->contains('id', auth()->user()->id) || auth()->user()->can('manage-tasks'))
                if($('#task_status').val() == 'Development Completed'){
                  finishState($('#task_status').val());
                  return;
                }
          @endif
            e.preventDefault();
            var taskId = $('#task-id').attr('data-id');
            var taskStatus = $('#task_status').val();
            if(taskStatus == 'Done') {
              $.ajax({
                method: 'POST',
                url: '/check-subtasks',
                data: {
                  'task_id':taskId,
                  'status':taskStatus
                },
                success: function(response) {
                  if(response.status) {
                    Swal.fire({
                      title: 'Do you want to close all subtasks too ?',
                      icon: 'warning',
                      showCancelButton: true,
                      showDenyButton: true,
                      confirmButtonText: 'Yes, Close all !',
                      denyButtonText: 'No, Only this task !',
                      cancelButtonText: 'Cancel'
                    }).then((result) => {
                      if (result.value) {
                          changeTaskStatus(taskId, taskStatus, 1);
                      } else {
                        if (result.value === false) {
                          changeTaskStatus(taskId, taskStatus);
                        } else {
                          reloadPage();
                        }
                      }
                    });                    
                  } else {
                    changeTaskStatus(taskId, taskStatus);
                  } 
                }
              });
            } else {
              changeTaskStatus(taskId, taskStatus);
            }
            
        });

        function changeTaskStatus(taskId, taskStatus, updateStatus = 0) {
          $.ajax({
            method: 'POST',
            url: '/change-status',
            data: {
              'task_id':taskId,
              'status':taskStatus,
              'updateStatus':updateStatus
            },
            success: function(response) {
              if(response.flag == false) {
                toastr.warning(response.message);
              } else {
                toastr.success(response.message, 'Changed');
              } 
              reloadPage();
            }
          });
        } 

        Dropzone.options.dropzoneForm = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 5, // MB
            dictDefaultMessage: "<strong>Drop files here or click to upload. </strong></br> (This is just a demo dropzone. Selected files are not actually uploaded.)",
        };

        $(document).on('click','.add-documents', function (e) {
          $('#dropzoneForm #upload_task_id').val($('#task-id').attr('data-id'));
        });

        $('#add_documents').on('hidden.bs.modal', function() {
           getDocuments();
        });

      </script>
@endsection