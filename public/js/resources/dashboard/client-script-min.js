

var timerRunning = $.cookie('timerRunning');
var totalSeconds = 0;
var interval;
var running_id = $.cookie('running_task');
var taskStartedBy = $.cookie('taskStartedBy');

loadSession();

function loadSession() {

    $.ajax({
        type: 'GET',
        url: '/check-session',
        data: {},
        success: function (response) {

            if (response.flag === true) {
                running_id = response.id;
                var td = $('#td_' + response.id);
                totalSeconds = parseInt(response.sec);
                console.log(response.id);
                setCookies(response.id);
                setTimer(totalSeconds);
                td.find('i').toggleClass('fa fa-play fa fa-stop');
            } else {
                removeCookies();
            }
        }
    });
}


/*
 *
 *Timer Controls
 */

$(document).on('click', '#start-button', function () {
    var obj = $(this);
    var td = obj.closest('td');
    console.log($.cookie('timerRunning'));
    console.log(td.attr('data-task-id'));
    console.log($.cookie('running_task'));
    if ($.cookie('timerRunning') == 'true' && td.attr('data-task-id') != $.cookie('running_task')) {
        Swal.fire({
            icon: 'warning',
            title: 'Timer is already running for a Task. Stop the timer to start new one'
        });
        return;
    } else if ($.cookie('timerRunning') == 'true' && td.attr('data-task-id') == $.cookie('running_task')) {
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

                stopSession();
                window.clearInterval(interval);
                totalSeconds = 0;
            }
        });
    } else if (typeof $.cookie('timerRunning') == 'undefined') {

        setCookies(td.attr('data-task-id'));

        $.ajax({
            type: 'POST',
            url: '/add-session',
            data: {
                'task-id': $.cookie('running_task')
            },
            success: function (response) {
                if (response.success) {
                }
            }
        });
        obj.find('i').toggleClass('fa fa-play fa fa-stop');
        setTimer(0);
    }
});
$('.chosen-select').chosen({
    width: "100%"
});
function stopSession() {
    var interval;
    $("#stop_session").modal('show');
    
    $(document).on('click', '#stop_session .continue-btn', function (evt) {

        evt.stopImmediatePropagation();
        evt.preventDefault();

        $.ajax({
            type: 'POST',
            url: '/stop-session',
            data: {
                'task-id': $.cookie('running_task'),
                'comment': $("#stop_session_comment").val()
            },
            success: function (response) {


                removeCookies();
                unsetTimer();
                $("#stop_session").modal('hide');
                $("#stop_session_comment").val('');
                toastr.success(response.message, 'Updated');
                location.reload();

            },
            error: function (error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        });
    })
}
function setCookies(task_id) {
    console.log(task_id);
    $.cookie('timerRunning', 'true', { expires: 1, path: '/' });
    $.cookie('taskStartedBy', currentUser, { expires: 1, path: '/' });
    $.cookie('running_task', task_id, { expires: 1, path: '/' });

}
function removeCookies() {
    $.removeCookie('timerRunning', { path: '/' });
    $.removeCookie('running_task', { path: '/' });
    $.removeCookie('taskStartedBy', { path: '/' });
}

function setTimer(totalSeconds) {

    interval = setInterval(() => {
        ++totalSeconds;
        $("#td_" + $.cookie('running_task')).find("#seconds").text(pad(totalSeconds % 60));
        $("#td_" + $.cookie('running_task')).find("#minutes").text(pad(parseInt((totalSeconds / 60) % 60)));
        $("#td_" + $.cookie('running_task')).find("#hours").text(pad(parseInt((totalSeconds / 60) / 60)));
    }, 1000);

}
function unsetTimer() {
    totalSeconds = 0;
    if (typeof interval != 'undefined') {
        window.clearInterval(interval);
    }
    $("#td_" + $.cookie('running_task')).find("#seconds").text("00");
    $("#td_" + $.cookie('running_task')).find("#minutes").text("00");
    $("#td_" + $.cookie('running_task')).find("#hours").text("00");
    $("#td_" + $.cookie('running_task')).find('i').toggleClass('fa fa-stop fa fa-play');
}
function pad(val) {
    var valString = val + "";
    if (valString.length < 2) {
        return "0" + valString;
    } else {
        return valString;
    }
}


window.addEventListener('focus', loadSession);
window.addEventListener('blur', unsetTimer);



    $(document).ready(function() {
        $('.dataTable').DataTable();
        
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $('.summernote').summernote({
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
     
    });


    $(document).on('click', '.create-modal' , function (e) {
        e.preventDefault();
        $('.datetimepicker').datepicker('setDate', new Date());
        $('#add_task').modal('show');
    });

    $(document).on('click', '.add-task', function(e) {
        toasterOption();
        e.preventDefault();
        $('.field-error').html('');
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );

        var data = new FormData($('#add_task_form')[0]);
        $.ajax({
            url: $('#add_task_form').attr('action'),
            data: data,
            type: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $('#add_task').modal('hide');
                $(".overlay").remove();
                toastr.success(response.message, 'Created');
            },
            error: function(error) {
                $(".overlay").remove();
                if (error.status == 422) {
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#label_' + field).html(error);
                        });
                    }
                  
                }
            }
        });
    });


 /**
     * Removing validation errors and reset form on model window close
     */
 $('#add_task').on('hidden.bs.modal', function() {
    $(this).find('.text-danger').html('');
    $('#add_task_form').trigger('reset');
    $('.summernote').summernote('reset');

    //}
});

$('#add_task').on('shown.bs.modal', function() {
    $('.summernote').summernote({
        tooltip: false,
        dialogsInBody: true,
        dialogsFade: false,
        callbacks: {
            onImageUpload: function(files, editor) {
                for (let i = 0; i < files.length; i++) {
                    sendFile(files[i], $(this));
                }
            }
        }
    });
});

