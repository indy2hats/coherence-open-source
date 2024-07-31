var timerRunning = $.cookie('timerRunning');
var currentUser = 1;
var intervel;
var timerStopped;
var active = 1;
/* Timer Start */
$(document).on('click','#start', function () {
    //check cookie has a timer running
    console.log($.cookie('timerRunning'));
    if(typeof $.cookie('timerRunning') == 'undefined'){
        setTimer(0);
        setCookies();
        addSession();
        document.getElementById('timer-button').innerHTML="STOP";
    }else{

        removeCookies();
        unsetTimer();
        $("#stop_session").modal('show');
    }
});

$('#stop_session').on('hidden.bs.modal', function() {
    updateSession();
 });

/* Stop Session */
$(document).on('click', '#stop_session .continue-btn', function(e) {
    openLoader();
    removeCookies();
    unsetTimer();
    $.ajax({
        type:'POST',
        url:'/stop-session',
        data:{'task-id':$('#task-id-timer').val(),
            'comment' : $("#stop_session_comment").val(),
            'session_type' : $("#stop_session_type").val()
        },
        success: function( response ) {
            closeLoader();
            $("#stop_session").modal('hide');

            $("#stop_session_comment").val('');
            toastr.success(response.message, 'Updated');
            reloadPage();
            if(finish_state) {
                finishState();
            }
        },
        error: function(error) {
            $(".overlay").remove();
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function(field, error) {
                    $('#label_' + field).html(error);
                });
            }
        }
    });

});
//function development complete

$(document).on('click','#development_complete', function () {
        if($(".checklist-link").length != $(".todo-completed").length) {
            Swal.fire({
              icon: 'error',
              title: 'Something went wrong!',
              text: 'Update all checklists before finishing task.'
            });
        } else {
            if($.cookie('timerRunning') == 'true'){
                finish_state = true;
                $("#stop_session").modal('show');
            } else {
                finishState();
            }
        }

});
//function addSession ajax
function addSession(){
    $.ajax({
        type:'POST',
        url:'/add-session',
        data:{'task-id':$('#task-id-timer').val()},
        success: function( response ) {
        if(response.success){
            $('#task_status').val(response.status).trigger('chosen:updated');
        }
        }
    });
}
//function updateSession ajax
function updateSession()
    {
        if(active == 1) {
            $('#start').prop('disabled', false);
            if(currentUser != $.cookie('taskStartedBy'))
            {
                removeCookies();
            }

            $.ajax({
              type:'GET',
              url:'/check-session',
              data:{'task-id':$('#task-id').attr('data-id')},
              success: function( response ) {
                if(response.flag == true)
                {
                    if(response.id != $('#task-id').attr('data-id'))
                    {
                        $('#start').prop('disabled', true);
                    }else{
                        totalSeconds = parseInt(response.sec);
                        setCookies();
                        setTimer(totalSeconds);
                        document.getElementById('timer-button').innerHTML="STOP";
                    }
                }else{
                   if(response.assigned == 'false'){
                    $('.timer-control').hide();
                       }
                       else{
                        $('.timer-control').show();
                       }
                    removeCookies();
                    $('#start').prop('disabled', false);
                }

              }
            });
            active = 0;
        }
}
function setCookies(){
    $.cookie('timerRunning', 'true', { expires: 1, path: '/' });
    $.cookie('taskStartedBy', currentUser, { expires: 1 ,path: '/'});
    $.cookie('running_task', $('#task-id-timer').val() , { expires: 1 ,path: '/'});

}
function removeCookies(){
    $.removeCookie('timerRunning', { path: '/' });
    $.removeCookie('running_task', { path: '/' });
    $.removeCookie('taskStartedBy', { path: '/' });
}

function setTimer(totalSeconds){

    interval = setInterval(() => {
        ++totalSeconds;
        document.getElementById("seconds").innerHTML = pad(totalSeconds % 60);
        document.getElementById("minutes").innerHTML = pad(parseInt((totalSeconds / 60) % 60));
        document.getElementById("hours").innerHTML = pad(parseInt((totalSeconds / 60) / 60));
    }, 1000);

}
function unsetTimer()
{
    var isTimerExist = document.getElementById("seconds");
    if(isTimerExist){
        active= 1;
        totalSeconds=0;
        if(typeof interval != 'undefined')
            window.clearInterval(interval);

        document.getElementById("seconds").innerHTML = "00";
        document.getElementById("minutes").innerHTML = "00";
        document.getElementById("hours").innerHTML = "00";
        document.getElementById('timer-button').innerHTML="START";
    }
}
function pad(val){
    var valString = val + "";
    if (valString.length < 2) {
    return "0" + valString;
    } else {
    return valString;
    }
}


window.addEventListener('focus', updateSession);
window.addEventListener('blur', unsetTimer);
