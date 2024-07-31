<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    var timerRunning = $.cookie('timerRunning');
    var totalSeconds = 0;
    var interval;
    var running_id = $.cookie('running_task');
    var currentUser = "<?php echo Auth::user()->id ?>";
    var taskStartedBy = $.cookie('taskStartedBy');
         
    loadSession();

    function loadSession()
    {
        
            $.ajax({
            type: 'GET',
            url: '{{route('checkSession')}}',
            data: {},
            success: function(response) {
                
                if (response.flag === true) {
                    running_id = response.id;
                    var td = $('#td_' + response.id);
                    totalSeconds = parseInt(response.sec);
                    console.log(response.id);    
                    setCookies(response.id);
                    setTimer(totalSeconds);
                    td.find('i').toggleClass('fa fa-play fa fa-stop');
                }else{
                    removeCookies();
                }
            }
        });
    }

    
    /*
     *
     *Timer Controls
     */

    $(document).on('click', '#start-button', function() {
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
                url: '{{route('addSession')}}',
                data: {
                    'task-id': $.cookie('running_task')
                },
                success: function(response) {
                    if (response.success) {
                    }
                }
            });
            obj.find('i').toggleClass('fa fa-play fa fa-stop');
            setTimer(0);
        }
    });
    function stopSession(){
        var interval;
        $("#stop_session").modal('show');
        $(document).on('click', '#stop_session .continue-btn', function(evt) {
            
            evt.stopImmediatePropagation();
            evt.preventDefault();
            
            $.ajax({
                        type: 'POST',
                        url: '{{route('stopSession')}}',
                        data: {
                            'task-id': $.cookie('running_task'),
                            'comment' : $("#stop_session_comment").val()
                        },
                        success: function(response) {
                            

                            removeCookies();
                            unsetTimer();
                            $("#stop_session").modal('hide');
                            $("#stop_session_comment").val('');
                            toastr.success(response.message, 'Updated');
                            
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
        })
    }
function setCookies(task_id){
    console.log(task_id);
    $.cookie('timerRunning', 'true', { expires: 1, path: '/' });
    $.cookie('taskStartedBy', currentUser, { expires: 1 ,path: '/'});
    $.cookie('running_task', task_id , { expires: 1 ,path: '/'});
                
}
function removeCookies(){
    $.removeCookie('timerRunning', { path: '/' });
    $.removeCookie('running_task', { path: '/'});
    $.removeCookie('taskStartedBy', { path: '/' });
}

function setTimer(totalSeconds){
    
    interval = setInterval(() => {
        ++totalSeconds;
        $("#td_"+$.cookie('running_task')).find("#seconds").text(pad(totalSeconds % 60));
        $("#td_"+$.cookie('running_task')).find("#minutes").text(pad(parseInt((totalSeconds / 60) % 60)));
        $("#td_"+$.cookie('running_task')).find("#hours").text(pad(parseInt((totalSeconds / 60) / 60)));
    }, 1000);
                
}
function unsetTimer()
{
    totalSeconds=0;
    if(typeof interval != 'undefined')
    window.clearInterval(interval);
    
    $("#td_"+$.cookie('running_task')).find("#seconds").text("00");
    $("#td_"+$.cookie('running_task')).find("#minutes").text("00");
    $("#td_"+$.cookie('running_task')).find("#hours").text("00");
    $("#td_"+$.cookie('running_task')).find('i').toggleClass('fa fa-stop fa fa-play');
}   
function pad(val){
    var valString = val + "";
    if (valString.length < 2) {
    return "0" + valString;
    } else {
    return valString;
    }
}


window.addEventListener('focus', loadSession);
window.addEventListener('blur', unsetTimer);

</script>