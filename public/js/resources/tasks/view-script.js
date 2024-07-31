
   checkExceedTime();
   checkExistingSession();
    $('.chosen-select').chosen({width: "100%"});
    var timerRunning = $.cookie('timerRunning');
    var currentUser = "<?php echo Auth::user()->id ?>";
    var totalSeconds=0;
    var finish_state = false;
    inputsLoader();
    $(document).ready(function(){

        $('.rejectionTable').DataTable();
        loadIchecks();
        timeTaken();
        sessionTime();

        $(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                // $('#create_task').modal('hide');
                $('#edit_task').modal('hide');
                $('#delete_task').modal('hide');
                
            }
        });


        $('body').on('click', ".checklist-link", function(e){
            e.preventDefault();
            var $this = $(this);
            openLoader();
            $.ajax({
                type:'POST',
                url:'/update-task-checklist',
                data:{
                    'id':$this.data('id'),
                    'status': $this.data('status'),
                    'type': $this.data('type'),
                },
                success: function( response ) {
                    closeLoader();
                    var button = $this.find('i');
                    var label = $this.next('span');
                    button.toggleClass('fa-check-square').toggleClass('fa-square-o');
                    label.toggleClass('todo-completed');
                    toastr.success(response.message, 'Updated');
                    $this.attr('data-status', response.status);
                }
            });
            
        });

        $(document).on("show.bs.modal", '.modal', function (event) {
            var zIndex = 100000 + (10 * $(".modal:visible").length);
            $(this).css("z-index", zIndex);
            setTimeout(function () {
                $(".modal-backdrop").not(".modal-stack").first().css("z-index", zIndex - 1).addClass("modal-stack");
            }, 0);
        }).on("hidden.bs.modal", '.modal', function (event) {
            console.log("Global hidden.bs.modal fire");
            $(".modal:visible").length && $("body").addClass("modal-open");
        });
        $(document).on('inserted.bs.tooltip', function (event) {
            var zIndex = 100000 + (10 * $(".modal:visible").length);
            var tooltipId = $(event.target).attr("aria-describedby");
            $("#" + tooltipId).css("z-index", zIndex);
        });
        $(document).on('inserted.bs.popover', function (event) {
            var zIndex = 100000 + (10 * $(".modal:visible").length);
            var popoverId = $(event.target).attr("aria-describedby");
            $("#" + popoverId).css("z-index", zIndex);
        });

        $(document).on('click', '.reply-submit', function() {
            $('body').removeClass('modal-open');
            $('body').css('padding-right','');
        });

        $(document).on('click', '.edit-submit', function() {
            $('body').removeClass('modal-open');
            $('body').css('padding-right','');
        });


        updateSession();

        $('body').on('focus', "#data_1 .input-group.date", function(){
            $(this).datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format:'dd/mm/yyyy'
            });
        });

        $('body').on("keydown", "input[id*='add-session-box-time'], input[id*='edit-session-box-time']", function (event) {
            if (event.shiftKey == true) {
                event.preventDefault();
            }

            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190 || event.keyCode == 110) {

            } else {
                event.preventDefault();
            }
            
            if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                event.preventDefault();
        });

        $(".js-range-slider").ionRangeSlider({
            onFinish: function (data) {
                var value = data.fromNumber;
                toasterOption();

                $.ajax({
                    type:'POST',
                    url:'/update-progress',
                    data:{
                        'taskId':$('#task-id').attr('data-id'),
                        'progress':value
                    },
                    success: function( response ) {
                        $('#completed').html(value);
                        toastr.success(response.message, 'Updated');
                    }
                });
            }
        });

        $(document).on('click', '#accept_button', function() {
            checkWhetherExceedsTimeWithReason($(this).attr('data-row-id'));
        });

        $(document).on('change', '#userSession, #userSessionType', function() {
            openLoader();
            $.ajax({
            method: 'POST',
            url: "/get-user-session",
            data: {
              'userId':$('#userSession').val(),
              'type':$('#userSessionType').val(),
              'taskId':$('#task-id').attr('data-id')
            },
            success: function(response) {
                closeLoader();
                $('.task-session').html(response.data);
                $('.chosen-select').chosen();
            }
          });
        });

        $(document).on('click', '#reject-button', function() {
            var rejectId = $(this).data('id');
            $('#reject_task_modal #reject_id').val(rejectId);
             $.ajax({
                method: 'POST',
                url: "/check-whether-exceeds-time-with-reason",
                data: {
                  'id':rejectId
                },
                success: function(response) {
                    if(response.flag == true){
                        $('#task_rejection_form').append('<div class="row"><div class="col-md-12"><div class="form-group"><label>Reason entered by the employee </label><textarea rows="4" class="form-control summernote" placeholder="" name="exceed_reason" id="comments">'+response.exceed_reason+'</textarea></div></div></div>');
                          $('.summernote').summernote();
                          $('#reject_task_modal').modal('show');
                    }
                    else{
                        $('#reject_task_modal').modal('show');
                    }
                }
            });
         });

        $(document).on('click', '.delete-task', function() {
            var deleteTaskId = $(this).data('id');
            var deleteTaskType = $(this).data('type');
            $('#delete_task #delete_task_id').val(deleteTaskId);
            $('#delete_task #delete_task_type').val(deleteTaskType);
        });

        $(document).on('click', '.admin_approve', function() {
            var taskId = $(this).data('id');
            $('#admin_approve_modal #approve_task_id').val(taskId);
        });

         /** Task Rejection - create modal */
        $(document).on('click', '#reject_task_modal .continue-btn', function(e) {
            e.preventDefault();
            openLoader();
            $.ajax({
                type: 'POST',
                url: $('#task_rejection_form').attr('action'),
                data: $('#task_rejection_form').serialize(),
                success: function(response) {
                    $('#reject_task_modal').modal('hide');
                    closeLoader();
                    toastr.success(response.message, 'Rejected');
                    reloadPage();
                },
                error: function(error) {
                    closeLoader();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#label_' + field).html(error);
                        });
                    }
                }
            });
        });

        $(document).on('click', '.delete-doc', function(e) {
            e.preventDefault();
            var $this = $(this);
            var deleteUrl = $(this).attr('href');
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                type: 'GET',
                url: deleteUrl,
                data: {},
                success: function(response) {
                    closeLoader();
                    $this.parents('.doc-repeat').remove();
                },
                error: function(error) {
                }
            });
        });
        

        /**
         * Delete task form - continue button action
         */
        $(document).on('click', '#delete_task .continue-btn', function() {
            toasterOption();
            var deleteTaskId = $('#delete_task #delete_task_id').val();
            openLoader();
            var deleteTaskType = $('#delete_task #delete_task_type').val();
            $('#delete_task').modal('hide');
            $.ajax({
                type: 'POST',
                url: '/delete-task-ajax',
                data: {
                    'taskId': deleteTaskId,
                    'projectId': $('#project_id').attr('data-id')
                },
                success: function(response) {
                    closeLoader();
                    toastr.success(response.message, 'Archived');
                    if(deleteTaskType == 'parent'){
                        window.location.href="/tasks";
                    } else {
                        $(".overlay").remove();
                        // reloadPage();
                        loadSubTask();
                    }
                },
                error: function(error) {
                    closeLoader();
                }
            });
        });

        $(document).on('click', '#admin_approve_modal .continue-btn', function() {
            toasterOption();
            var taskId = $('#admin_approve_modal #approve_task_id').val();
            openLoader();
            $('#admin_approve_modal').modal('hide');
            $.ajax({
                type: 'POST',
                url: '/admin-task-approve',
                data: {
                    'taskId': taskId,
                },
                success: function(response) {
                    closeLoader();
                    reloadPage();
                },
                error: function(error) {
                    closeLoader();
                }
            });
        });



        $(document).on('click','.create-session', function (e) {
            e.preventDefault();
            createSession();
        });

        $(document).on('click', '.edit-task-session', function() {
            var sessionId = $(this).data('id');
            var editUrl = "/task-session/" + sessionId + '/edit';
            openLoader();
            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $('#edit_task_session').html(data);
                    inputsLoader();
                    closeLoader();
                    $('#edit_task_session').modal('show');
                }
            });

        });

        $(document).on('click','.edit-session', function (e) {
            e.preventDefault();
            editSession();
        });

        


        /**
         * Removing validation errors and reset form on model window close
         */
        $('#add-session').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#create_session_form').trigger('reset');
        });


        $(document).on('keyup','#add-session-box-time', function (event) {
            if (event.keyCode === 13) {          
                createSession();
            }
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
            openLoader();
            $.ajax({
                method: 'DELETE',
                url: '../task-session/' + deleteSessionId,
                data: {},
                success: function(response) {
                  closeLoader();
                  $('#delete_task_session').modal('hide');
                  toastr.success(response.message, 'Deleted');
                  reloadPage();
                }
            });
        });

        /**
         * Task edit form loading when clicking edit
         */
         $(document).on('click','.edit-task', function(e) {
            e.preventDefault();
            openLoader();
            var taskId = $(this).data('id');
            var editUrl = '../tasks/' + taskId + '/edit';

            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $('#edit_task').html(data);
                    inputsLoader();
                    // typeAhead();
                    loadIchecks();
                    $('.summernote').summernote();
                    closeLoader();
                    $("#edit_task").modal('show');
                }
            });
        });

        $(document).on('keyup','#edit-session-box-time', function (event) {
            if (event.keyCode === 13) {          
              editSession();
          }
        });


        $(document).on('click','#edit-session-box-time', function () {
            $('#edit-session-box-time').val("");
        });

        /** to update on enter key */
        $(document).on('keyup', '#edit_task_title', function(event) {
            if (event.keyCode === 13) {
                $('.edit-task').click();
            }
        });

        $(document).on('keyup', '#edit_estimated_time', function(event) {
            if (event.keyCode === 13) {
                $('.edit-task').click();
            }
        });

        $(document).on('keyup', '#edit_url', function(event) {
            if (event.keyCode === 13) {
                $('.edit-task').click();
            }
        });

        $(document).on('keyup', '#percent_complete', function(event) {
            if (event.keyCode === 13) {
                $('.edit-task').click();
            }
        });

         $(function () {
            $('#timepicker').datetimepicker({
                format:'HH:mm'
            });
        });

        if($('#percent_complete').val() == "100" ){
            $('#start').prop('disabled', true);
        }
        /**
         * Edit task form - submit button action
         */
        $(document).on('click','.update-task', function(e) {
            toasterOption();
            $('.field-error').html('');
            e.preventDefault();
            openLoader();
            var data = new FormData($('#edit_task_form')[0]);
            $.ajax({
                type:'POST',
                url:$('#edit_task_form').attr('action'),
                data: data,
                contentType: false,
                cache: false,
                processData:false,
                success: function( response ) {
                    closeLoader();
                    $('#edit_task').modal('hide');
                    toastr.success(response.message, 'Updated');
                    // reloadPage();
                    loadSubTask();
                    loadAssignees();
                    if($('.update-task').attr('data-reload')=='true'){
                        setInterval(function() {location.reload()},500);
                        }
                },
                error: function(error) {
                  closeLoader();
                    if(error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function (field, error) {
                            $('#label_'+field).html(error);
                        });
                    }
                }
            });       
        });
    });

    function reloadPage() {
        if (typeof interval != 'undefined') {
            clearInterval(interval);
        }
        $.ajax({
            type:'POST',
            url:'/update-task-detail',
            data:{
                'task_id':$('#task-id').attr('data-id')
            },
            success: function( response ) {
                $('.main').html(response.data);
                $(".js-range-slider").ionRangeSlider({
                    onFinish: function (data) {
                        var value = data.fromNumber;
                        toasterOption();

                        $.ajax({
                            type:'POST',
                            url:'/update-progress',
                            data:{
                                'taskId':$('#task-id').attr('data-id'),
                                'progress':value
                            },
                            success: function( response ) {
                                $('#completed').html(value);
                                toastr.success(response.message, 'Updated');
                            }
                        });
                    }
                });
                inputsLoader();
                updateSession();
                loadIchecks();
                timeTaken();
                sessionTime();
                $('.rejectionTable').DataTable();
            }
        });
    }

    function typeAhead(){
        $.get('get-typhead-data-project',
            function(response) {
                var name = [],id = [];
                for (var i = response.data.length - 1; i >= 0; i--) {
                    name.push(response.data[i]['project_name']);
                    id.push(response.data[i]['project_id']);
                }
                $(".typeahead_name").typeahead({
                    source: name
                });
                $(".typeahead_id").typeahead({
                    source: id
                });
            }, 'json');
    }
    function inputsLoader() {
        $('.chosen-select').chosen({
                width: "100%"
            });
        $('.datetimepicker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true
        });
        $('.summernote').summernote({
            tooltip:false,
            dialogsInBody: true,
            dialogsFade: false,
            callbacks:{
                onImageUpload: function(files, editor) {
                    for(let i=0; i < files.length; i++) {
                        sendFile(files[i], $(this));
                    }
                }
            }
        });
        $('#data_1 .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            format:'dd/mm/yyyy'
        });
      $('.clockpicker').clockpicker();
    }

    function finishState(){
        var flag = checkWhetherExceedsTime();
    }

    function changeStatusFinish() {
         $.ajax({
                method: 'POST',
                url: '/change-status-finish',
                data: {
                    'task_id':$('#task-id').attr('data-id'),
                    'status':'Development Completed'
                },
                success: function(response) {
                    reloadPage();
                    toastr.success(response.message, 'Updated');
                    finish_state = false;
                }
            });
    }

    function getSessionList() {
        $.ajax({
            type:'POST',
            url:'/get-tasks-session',
            data:{
                'taskId': $('#task-id').attr('data-id')
            },
            success: function( response ) {
                $('.main').html(response.data);  
                inputsLoader();
            }
        });
    }

    function getDocuments() {
        $.ajax({
            type:'POST',
            url:'/get-documents',
            data:{
                'task_id': $('#task-id').attr('data-id')
            },
            success: function( response ) {
                $('.documents-div').html(response.data);  
            }
        });
    }

    function checkExceedTime() {
        $.ajax({
            type: 'POST',
            url: '/check-exceed-time',
            data: {
                'task_id':$('#task-id').attr('data-id')
            },
            success: function(response) {
              if(response.flag == true){
                Swal.fire({
                  icon: 'warning',
                  title: 'You have passed the estimate. Have you talked to your manager?'
                });
              }
            }
        });
    }

    function checkExistingSession() {
        $.ajax({
            type: 'POST',
            url: '/check-existing-session',
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

    function createSession() {
        checkExistingSession();
        $('.field-error').html('');
        openLoader();
        $.ajax({
            type: 'POST',
            url: $('#create_session_form').attr('action'),
            data: $('#create_session_form').serialize(),
            success: function(response) {
                closeLoader();
                if(response.success) {
                    $('#add-session').modal('hide');
                    reloadPage();
                    toastr.success(response.message,'Added');
                } else {
                    toastr.error(response.message,'Already Added');
                }
            },
            error: function(error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }           
        });
    }

    function editSession() {
        $('.field-error').html('');
        openLoader();
        $.ajax({
            type: 'PATCH',
            url: $('#edit_session_form').attr('action'),
            data: $('#edit_session_form').serialize(),
            success: function(response) {
                closeLoader();
                $('#edit_task_session').modal('hide');
                reloadPage();
                checkExistingSession();
                toastr.success(response.message,'Updated');
            },
            error: function(error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_edit_' + field).html(error);
                    });
                }
            } 
        });
    }

    

    function sendFile(file, editor, welEditable) {
        var  data = new FormData();
        data.append("file", file);
        var url = '/content-image-upload';
        openLoader();
        $.ajax({
            data: data,
            type: "POST",
            url: url,
            cache: false,
            contentType: false,
            processData: false,
            success: function(url) {
                closeLoader();
                editor.summernote('editor.insertImage', url);
            }
        });
    }

    function loadIchecks(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $('.check-all').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $('.check-all').on('ifChecked', function (event) {
            $(this).parents('tr').find('.i-checks').iCheck('check');
            triggeredByChild = false;
        });

        $('.check-all').on('ifUnchecked', function (event) {
            if (!triggeredByChild) {
                $(this).parents('tr').find('.i-checks').iCheck('uncheck');
            }
            triggeredByChild = false;
        });
        // Removed the checked state from "All" if any checkbox is unchecked
        $('.i-checks').on('ifUnchecked', function (event) {
            triggeredByChild = true;
            $(this).parents('tr').find('.check-all').iCheck('uncheck');
        });

        $('.i-checks').on('ifChecked', function (event) {
            if ($(this).parents('tr').find('.i-checks').filter(':checked').length == $(this).parents('tr').find('.i-checks').length) {
                $(this).parents('tr').find('.check-all').iCheck('check');
            }
        });

        $(".check-all").each(function() {
            if ($(this).parents('tr').find('.i-checks').filter(':checked').length == $(this).parents('tr').find('.i-checks').length) {
                $(this).iCheck('check');
            }
        });
    }

    function unloadIchecks(){
        $('.i-checks').iCheck('uncheck');
        $(".check-all").iCheck('uncheck');
    }

    function timeTaken(){
        $(".time-taken").each(function() {
            var count = $(this).data('count');
            var diff = $(this).data('total');
            var timer = $(this).children('strong');
            var totaltimer = $("#total_time");
            if (count > 0) {

                $.each($(this).data('starts').split(','), function(index, value) { 
                    diff = diff + (( new Date() - new Date(value) ) / 1000 / 60 / 60) ;
                });

                var seconds_left = diff*60*60;
                var hours = 0;

                var countdownrefesh = setInterval(function () {

                    // Add one to seconds
                    seconds_left = seconds_left + count;

                    hours_left = hours*3600+seconds_left;

                    hours = parseInt(hours_left / 3600);
                    seconds_left = seconds_left % 3600;

                    minutes = parseInt(seconds_left / 60);

                    minutesPercent = parseInt(minutes/60*100);

                    t = hours+"."+minutesPercent+" hrs ";
                    timer.html(t)

                    t = hours + "h " + minutes + "m";
                    totaltimer.html(t)

                }, 1000);
            }
            
        });
    }

    function sessionTime(){
        $(".time").each(function() {
            var diff = ( new Date() - new Date($(this).data('start')) ) / 1000 / 60 ;

            var diff = diff+$(this).data('total');

            var seconds_left = diff*60;
            var hours = 0;


            var timer = $(this).children('.timer');

            var countdownrefesh = setInterval(function () {

                // Add one to seconds
                seconds_left = seconds_left + 1;

                hours_left = hours*3600+seconds_left;

                hours = parseInt(hours_left / 3600);
                seconds_left = seconds_left % 3600;

                minutes = parseInt(seconds_left / 60);

                t = hours + "h " + minutes + "m";
                timer.html(t)

            }, 1000);
        });
    }

    function loadSubTask() {
        $.ajax({
            type:'POST',
            url:'/get-sub-task-list',
            data:{
                'task_id':$('#task-id').attr('data-id')
            },
            success: function(response) {
               $('.sub-task-div').html(response.data);
               $('.dataTable').DataTable();
               loadTaskStatus();
            },
            error: function(error) {
               
            } 
        });
    }

    function loadAssignees() {
        $.ajax({
            type:'POST',
            url:'/get-assigness-list',
            data:{
                'task_id':$('#task-id').attr('data-id')
            },
            success: function(response) {
               $('.assignees').html(response.data);
            },
            error: function(error) {
               
            } 
        });
    }

    function loadTaskStatus() {
        $.ajax({
            type:'POST',
            url:'/get-task-status',
            data:{
                'task_id':$('#task-id').attr('data-id')
            },
            success: function(response) {
               $('.status-dropdown').html(response.data);
               $('.chosen-select').chosen({
                    width: "100%"
                });
            },
            error: function(error) {
               
            } 
        });
    }

    function checkWhetherExceedsTime() {
        $.ajax({
            type:'POST',
            url:'/check-whether-exceeds-time',
            data:{
                'task_id':$('#task-id').attr('data-id')
            },
            success: function(response) {
                if(response.flag == true){
                    $('#exceed_time').modal({backdrop: 'static', keyboard: false});
                    return false;
                }
                else{
                    changeStatusFinish();
                }
            },
            error: function(error) {
               
            } 
        });
    }

    $(document).on('click', '#exceed_time .continue-btn', function() {
        $.ajax({
            type:'POST',
            url:'/add-time-exceed-reason',
            data:{
                'task_id':$('#task-id').attr('data-id'),
                'reason':$('#exceed_time .exceed-reason').val()
            },
            success: function(response) {
               $('#exceed_time').modal('hide') ;
               changeStatusFinish();
            },
            error: function(error) {
               if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_exceed_' + field).html(error);
                    });
                }
            } 
        });
    });

    function acceptContinue(row_id) {
            if($(".dev-pending").length > 0 || $(".rev-pending").length > 0) {
                Swal.fire({
                  icon: 'error',
                  title: 'Something went wrong!',
                  text: 'Checklists not completed.'
                });
            } else if($("#approver_status").attr('data-total') > $("#approver_status").attr('data-approved')) {
                Swal.fire({
                  icon: 'error',
                  title: 'Something went wrong!',
                  text: 'Admins not approved the task yet.'
                });
            } else {
                $.ajax({
                method: 'POST',
                url: "/accept-completion",
                data: {
                  'id':row_id
                },
                success: function(response) {
                  toastr.success(response.message, 'Accepted');
                  reloadPage();
                }
              });
            }
        }


    function checkWhetherExceedsTimeWithReason(row_id) {
        $.ajax({
            method: 'POST',
            url: "/check-whether-exceeds-time-with-reason",
            data: {
              'id':row_id
            },
            success: function(response) {
                if(response.flag == true){
                    Swal.fire({
                      title: 'Exceeds Estimated Time!',
                      text: "Reason: ",
                      html: response.exceed_reason,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonText: 'Yes, Accept!',
                      cancelButtonText: 'No, Reject!',
                      reverseButtons: true
                    }).then((result) => {
                      if (result.value) {
                       acceptContinue(row_id);
                      } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                      ) {
                        $('#reject_task_modal #reject_id').val(row_id);
                          $('#task_rejection_form').append('<div class="row"><div class="col-md-12"><div class="form-group"><label>Reason entered by the employee </label><textarea rows="4" class="form-control summernote" placeholder="" name="exceed_reason" id="comments">'+response.exceed_reason+'</textarea></div></div></div>');
                          $('.summernote').summernote();
                          $('#reject_task_modal').modal('show');
                      }
                    });
                  
                }
                else{
                    acceptContinue(row_id);
                }
            }
          });
    }


