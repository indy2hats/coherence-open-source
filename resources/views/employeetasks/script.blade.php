<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>

  checkExistingSession();


  	$(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                $('#add-session').modal('hide');
                $('#edit_task_session').modal('hide');
                $('#delete_task_session').modal('hide');
                $('#show-project-files').modal('hide');
                $('#edit_task_session').modal('hide');
                

            }
        });
     function inputsLoader() {
      
        $('#data_1 .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format:'yyyy-mm-dd'
      });
      $('.clockpicker').clockpicker();
      // $('.sessionTable').DataTable();
      $('.chosen-select').chosen({
                width: "100%"
            });
    }

      var timerRunning=false;
      var totalSeconds=0;
    
    

    inputsLoader();

    $(document).on('click', '.edit-task-session', function() {
        var sessionId = $(this).data('id');
        var editUrl = '../../task-session/' + sessionId + '/edit';

        $.ajax({
            type: 'GET',
            url: editUrl,
            data: {},
            success: function(data) {
                $('#edit_task_session').html(data);
                inputsLoader();
            }
        });

    });


    /**
     * Adding client id to hidden text field in delete model 
     */
    $(document).on('click', '#delete-session', function() {
      var deleteSessionId = $(this).data('id');
      $('#delete_task_session #delete_session_id').val(deleteSessionId);
    });

    /**
     * Delete model continue button action
     */
    $(document).on('click', '#delete_task_session .continue-btn', function() {
      var deleteSessionId = $('#delete_task_session #delete_session_id').val();

      $.ajax({
        method: 'DELETE',
        url: '../task-session/' + deleteSessionId,
        data: {},
        success: function(response) {
          $('#delete_task_session').modal('hide');
          toastr.success(response.message, 'Deleted');
          getSessionList();
        }
      });
    });

    $(document).on('change','#task_status', function (e) {
        if($('#task_status').val() == 'Development Completed'){
          finishState();
          return;
        }
        e.preventDefault();
        $.ajax({
        method: 'POST',
        url: '{{route('changeStatus')}}',
        data: {
          'task_id':$('#task-id').attr('data-id'),
          'status':$('#task_status').val()
        },
        success: function(response) {
          toastr.success(response.message, 'Changed');
          getSessionList();
        }
      });
    });

    $(document).on('click','.create-session', function (e) {
        e.preventDefault();
        createSession();
    });

    $(document).on('keyup','#add-session-box-time', function (event) {
        if (event.keyCode === 13) {          
          createSession();
      }
    });


    $(document).on('click','.edit-session', function (e) {
        e.preventDefault();
        editSession();
    });

    $(document).on('keyup','#edit-session-box-time', function (event) {
        if (event.keyCode === 13) {          
          editSession();
      }
    });


    $(document).on('click','#edit-session-box-time', function () {
        $('#edit-session-box-time').val("");
      });

  function createSession() {
    checkExistingSession();
      $('.field-error').html('');
        
        $.ajax({
            type: 'POST',
            url: $('#create_session_form').attr('action'),
            data: $('#create_session_form').serialize(),
            success: function(response) {
              $('#add-session').modal('hide');
              getSessionList();
              toastr.success(response.message,'Added');
            },
            error: function(error) {
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#label_' + field).html(error);
                        });
                    }
                }           
        });
  }

  /**
         * Removing validation errors and reset form on model window close
         */
        $('#add-session').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#create_session_form').trigger('reset');
        });

  function checkExistingSession() {
    $.ajax({
            type: 'POST',
            url: '{{route('checkExistingSession')}}',
            data: {},
            success: function(response) {
              if(response.flag){
                $('#start').prop('disabled', true);
                Swal.fire({
                  icon: 'warning',
                  title: 'There exists a session which is not properly stopped. Please update the session manualy.<br><br>Task Title: '+response.title+'<br>Date: '+response.date,
                  footer: '<a href="/tasks/'+response.task_id+'">Go To Task: ' + response.title + '</a>'
                });
              }
            }
        });
  }
  
  function editSession() {
      $('.field-error').html('');
        
        $.ajax({
            type: 'PATCH',
            url: $('#edit_session_form').attr('action'),
            data: $('#edit_session_form').serialize(),
            success: function(response) {
              $('#edit_task_session').modal('hide');
              getSessionList();
              checkExistingSession();
              toastr.success(response.message,'Updated');
            }
        });
  }
  /*
*
*Timer Controls
*/
$(document).on('click','#start', function () {
  if(!timerRunning){
    $.ajax({
      type:'POST',
      url:'{{route('addSession')}}',
      data:{'task-id':$('#task-id-timer').val()},
      success: function( response ) {
        if(response.success){
          $('#task_status').val(response.status).trigger('chosen:updated');
        }
      }
    });
    timerRunning=true;
    document.getElementById('timer-button').innerHTML="STOP";
    interval = setInterval(setTime, 1000);

    function setTime() {
      ++totalSeconds;
      document.getElementById("seconds").innerHTML = pad(totalSeconds % 60);
      document.getElementById("minutes").innerHTML = pad(parseInt((totalSeconds / 60) % 60));
      document.getElementById("hours").innerHTML = pad(parseInt((totalSeconds / 60) / 60));
    }
    function pad(val) {
      var valString = val + "";
      if (valString.length < 2) {
        return "0" + valString;
      } else {
        return valString;
      }
    }
  }
  else{
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, stop session!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type:'POST',
          url:'{{route('stopSession')}}',
          data:{'task-id':$('#task-id-timer').val()},
          success: function( response ) {
            console.log(response.message);
            getSessionList();
          }
        });
        Swal.fire(
          'Stopped!',
          'Your session has been saved.',
          'success'
        );
        timerRunning=false;
        totalSeconds=0;
        clearInterval(interval);
         document.getElementById("seconds").innerHTML = "00";
         document.getElementById("minutes").innerHTML = "00";
         document.getElementById("hours").innerHTML = "00";
        document.getElementById('timer-button').innerHTML="START";
      }
    });
  }
});
$(document).on('click','#development_complete', function () {
  if(timerRunning){
  $.ajax({
          type:'POST',
          url:'{{route('stopSession')}}',
          data:{'task-id':$('#task-id-timer').val()},
          success: function( response ) {
            timerRunning=false;
            totalSeconds=0;
            clearInterval(interval);
            document.getElementById("seconds").innerHTML = "00";
            document.getElementById("minutes").innerHTML = "00";
            document.getElementById("hours").innerHTML = "00";
            document.getElementById('timer-button').innerHTML="START";
          }
        });
  }
  finishState();
});

  $(function () {
    $('#timepicker').datetimepicker({
        format:'HH:mm'
    });
});
  if($('#percent_complete').val() == "100" ){
    $('#start').prop('disabled', true);
  }

    $.ajax({
      type:'GET',
      url:'{{route('checkSession')}}',
      data:{'task-id':$('#task-id').attr('data-id')},
      success: function( response ) {
        if(response.flag != false && response.id != $('#task-id').attr('data-id')){
          $('#start').prop('disabled', true);
        }
        else if(response.flag == true && response.id == $('#task-id').attr('data-id'))
        {
          totalSeconds = parseInt(response.sec);
          timerRunning=true;
          document.getElementById('timer-button').innerHTML="STOP";
          interval = setInterval(setTime, 1000);
          function setTime() {
            ++totalSeconds;
            document.getElementById("seconds").innerHTML = pad(totalSeconds % 60);
            document.getElementById("minutes").innerHTML = pad(parseInt((totalSeconds / 60) % 60));
            document.getElementById("hours").innerHTML = pad(parseInt((totalSeconds / 60) / 60));
          }
          function pad(val) {
            var valString = val + "";
            if (valString.length < 2) {
              return "0" + valString;
            } else {
              return valString;
            }
          }
        }
      }
    });

    function getSessionList() {
        $.ajax({
                type:'POST',
                url:'{{ route('getTaskSession') }}',
                data:{
                    'taskId': $('#task-id').attr('data-id')
                },
                success: function( response ) {
                    $('.main').html(response.data);  
                    inputsLoader();
                }
            });
    }

    function finishState(){
      $.ajax({
              method: 'POST',
              url: '{{route('changeStatusFinish')}}',
              data: {
                'task_id':$('#task-id').attr('data-id'),
                'status':'Development Completed'
              },
              success: function(response) {
                toastr.success(response.message, 'Updated');
                getSessionList();
              }
            });
    }
</script>
