$(document).ready(function() {
    $(window).scroll(sticky_relocate);
       sticky_relocate();
    $('#start_date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#end_date').datepicker('setStartDate', minDate);
    });
    $('#end_date').datepicker({

        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: $('#start_date').datepicker('getDate')
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#start_date').datepicker('setEndDate', maxDate);
    });

    fetchTasks();
    
});

function sticky_relocate() {
    var containerWidth = $(".fixed-sec").width();
    var window_top = $(window).scrollTop();
    var div_top = $('#sticky-anchor').offset().top;
    if (window_top > div_top) {
       $('.fixed-sec').addClass('stick');
       $('#sticky-anchor').addClass('height-set');
       $('.stick').css('width', containerWidth);
    } else {
       $('.fixed-sec').removeClass('stick');
       $('.fixed-sec').css('width', 'auto');
       $('#sticky-anchor').removeClass('height-set');
    }
 }

$(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    fetchTasks(page);
});

function fetchTasks(page=1,modalopen=false){
    var project_id = $('#project_id').val();
    var start_date=$("#start_date").val();
    var end_date=$("#end_date").val();
    var task_type=$("#task_type").val();
    var priority=$("#priority").val();
    openLoader();
    $.ajax({
        url: "/gantt-task-lists",
        data: {
            'id':project_id,
            'start_date': start_date,
            'end_date': end_date,
            'task_type': task_type,
            'priority': priority,
            'page': page
        },
        type: 'POST',
        success: function(response) {
            $("#gantt-task-list").html(response.data);
            fetchProjectUsers();
            rowHeight();
            fetchResources();
            checkTaskChoosed(modalopen);
            closeLoader();
        },
        error: function(error) {
            closeLoader();
            toastr.warning('Sorry, Please reload this page.', 'Failed');
        }
    });
}

function enterEdit(event, id, type) {
    if (event.which === 13) {
        updateTask(id, type);
    }
}

function checkTaskChoosed(modalopen=false) {
    $("#assignDaysModal").modal('hide');
    if(!modalopen){
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    }
    var taskid = $('input[type="radio"]:checked').val();
    if (taskid) {
        fetchResources();
    }
}

function resourceHourEnter(event, userid, key, date) {
    if (event.which === 13) {
        updateUserTaskHour(userid, key, date);
    }
}

function rowHeight() {
    var maxHeight = 0;
    $('.mainrow').each(function() {
        if ($(this).height() > maxHeight) {
            maxHeight = $(this).height();
        }
    });
    $('table tr').height(maxHeight);
}

function collapsebox(id) {
    $("#collase-icon" + id).toggleClass('fa-plus-circle fa-minus-circle');
    $(".sub-table-" + id).toggleClass('collase-active');
    $("#leftbar" + id).toggleClass('active-row');
    $("#rightbar" + id).toggleClass('active-row');
}

function collapseResources(roleid) {
    $("#collase-icon" + roleid).toggleClass('fa-plus-circle fa-minus-circle');
    $(".sub-table-" + roleid).toggleClass('table-row');
    $(".sub-table-" + roleid).toggleClass('display-none');
}

$("thead, tfoot").on('click', function() {
    editDisable();
});

$(document).on('mousedown', function(event) {
    var target = $(event.target);
    if (!target.closest('table').length) {
        editDisable();
    }
});

function editDisable() {
    $(".task-text").css('display', 'block');
    $(".task-update").css('display', 'none');
}

function editField(id, type) {
    $(".task-text").css('display', 'block');
    $(".task-update").css('display', 'none');
    $("#" + type + "-text-" + id).css('display', 'none');
    $("#" + type + "-input-" + id).css('display', 'block');
    editLoadFunction();
    changeRowHeight(id);
}

function editLoadFunction(){
    $('.datetimepickernew').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true
    });
    $('.chosen-select').chosen({
        width:"100%"
    });
}



function changeRowHeight(id) {
    var maxHeight = 0;
    $('#mainrow-left' + id).each(function() {
        if ($(this).height() > maxHeight) {
            maxHeight = $(this).height();
        }
    });
    $('#mainrow-right' + id).height(maxHeight);
}




function updateTask(id, type) {
    var resources = $('#assigned_users' + id).val();
    var percentage = $('#task-percentage' + id).val();
    var startdate = $('#task-startdate' + id).val();
    var enddate = $('#task-enddate' + id).val();
    var priority = $('#task-priority' + id).val();
    var estimation = $('#task-estimation' + id).val();
    if (type == 'percentage') {
        if (percentage > 100) {
            toastr.warning('Please enter below 100.', 'Failed');
            return false;
        }
    }
    openLoader();
    $.ajax({
        url: "/gantt-task-update",
        data: {
            'task_id': id,
            'type': type,
            'resources': resources,
            'percentage': percentage,
            'startdate': startdate,
            'enddate': enddate,
            'estimation': estimation,
            'priority': priority
        },
        type: 'POST',
        success: function(response) {
            closeLoader();
            toastr.success('Task has been updated successfully', 'Task updated');
            $("#" + type + "-text-" + id).html(response);
            if (type == 'percentage') {
                $("#task-progress-bar" + id).css('width', response)
                $("#task-progress-value" + id).html(response)
            } else if (type == "startdate" || type == "enddate") {
                fetchTasks();
            }
            $(".task-text").css('display', 'block');
            $(".task-update").css('display', 'none');
            changeRowHeight(id);
            fetchProjectUsers();
            fetchResources();
        },
        error: function(error) {
            closeLoader();
            toastr.warning('Sorry, Please reload this page.', 'Failed');
        }
    });
}

function fetchResources() {
    $("#assign_days_btn").css('display','none');
    $("#assign_day_btn").css('display','none');
    $(".assign_days_field").css('display','none');
    var project_id = $('#project_id').val();
    var task_id = $('input[type="radio"]:checked').val();
    var start_date = $('input[type="radio"]:checked').data('start_date');
    var end_date = $('input[type="radio"]:checked').data('end_date');
    if(!start_date || !end_date){
        start_date=$("#start_date").val();
        end_date=$("#end_date").val();
    }
    openLoader();
    $.ajax({
        url: "/fetch-gantt-resources",
        data: {
            'start_date': start_date,
            'end_date': end_date,
            'project_id': project_id
        },
        type: 'POST',
        success: function(response) {
            closeLoader();
            $("#gantt-resource-management").html(response.data);
            if (task_id != undefined && task_id != 0) {
                $("#resource-reset").show();
                $(".resource-icon").css('display', 'block');
                $("#taskTable td:first-child").removeClass('blink-border')
                $("#taskTable th:first-child").removeClass('blink-border')
            }

        },
        error: function(error) {
            closeLoader();
            toastr.warning('Sorry, Please reload this page.', 'Failed');
        }
    });
}

function dayAssign(){
    var task_id = $('input[type="radio"]:checked').val();
    if (!task_id || task_id == 0) {
        $("#assign_days_btn").css('display','none');
        $(".assign_days_field").css('display','none');
        $("#assign_day_btn").css('display','block');
        editLoadFunction();
        $("#task_estimate_alert").html('');
        $("#assignDaysModal").modal('show');
    }
}

function updateUserTaskHour(userid, key, date) {

    var hour = $("#resource_hour_" + userid + '_' + key).val();
    var previous_hour = $("#resource_hour_" + userid + '_' + key).attr('data-prevalue');
    if(previous_hour==hour){
        return false;
    }
    var task_id = $('input[type="radio"]:checked').val();
    if (!task_id || task_id == 0) {
        toastr.warning('Sorry, Please choose a task.', 'Failed');
        $("#taskTable td:first-child").addClass('blink-border')
        $("#taskTable th:first-child").addClass('blink-border')
        $('html, body').animate({
            scrollTop: $('#taskTable').offset().top
        }, 1000);
        return false;
    } else if (!hour || hour<=0) {
        return false;
    } else if (hour > 8) {
        $("#resource_hour_" + userid + '_' + key).val(previous_hour);
        toastr.warning('Sorry, Please enter below 8 hrs.', 'Failed');
        return false;
    } else {
        openLoader();
        $.ajax({
            url: "/update-task-hours",
            data: {
                'userid': userid,
                'task_id': task_id,
                'hour': hour,
                'date': date
            },
            type: 'POST',
            success: function(response) {
                closeLoader();
                if (response.status) {
                    toastr.success(response.data, 'Task updated');
                } else {
                    toastr.warning(response.data, 'Failed');
                }
                fetchResources()
            },
            error: function(error) {
                closeLoader();
                toastr.warning('Sorry, Please reload this page.', 'Failed');
            }
        });
    }
}

function assignDaysHours() {
    var userid = $("#assign_user_id").val();
    if (userid) {
        var hour = $("#assign_hours").val();
        var task_id = $('input[type="radio"]:checked').val();
        var assign_start_date = $("#assign_start_date").val();
        var assign_end_date = $("#assign_end_date").val();

        if (!task_id || task_id == 0) {
            toastr.warning('Sorry, Please choose a  task.', 'Failed');
            $("#taskTable td:first-child").addClass('blink-border')
            $("#taskTable th:first-child").addClass('blink-border')
            $('html, body').animate({
                scrollTop: $('#taskTable').offset().top
            }, 1000);
            return false;
        } else if (!assign_start_date || !assign_end_date ) {
            toastr.warning('Date field is mandatory.', 'Failed');
            return false;
        } else if (!hour || hour <= 0) {
            toastr.warning('Sorry, Please enter valid hours.', 'Failed');
            return false;
        } else {
            closeLoader();
            $.ajax({
                url: "/update-alldays-task-hours",
                data: {
                    'userid': userid,
                    'task_id': task_id,
                    'hour': hour,
                    'start_date': assign_start_date,
                    'end_date': assign_end_date
                },
                type: 'POST',
                success: function(response) {
                    closeLoader();
                    toastr.success('Hours has been updated successfully', 'Success');
                    $("#assignDaysModal").modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    fetchResources()
                },
                error: function(error) {
                    closeLoader();
                    toastr.warning('Sorry, Please reload this page.', 'Failed');
                }
            });
        }
    }
}



function resourceHourEdit(userid, key) {
    $(".resource-text").css('display', 'block');
    $(".resource-update").css('display', 'none');
    $("#resource-text-" + userid + '-' + key).css('display', 'none');
    $("#resource-input-" + userid + '-' + key).css('display', 'block');
}

function taskModal(userid, key, content) {
    var task_id = $('input[type="radio"]:checked').val();
    $("#gantt-task-content").html(content);
    $("#taskModal").modal('show');
    
}

function checkTaskAvailability(){
    $("#task_estimate_alert").html('');
    var start_date = $("#assign_start_date").val();
    var end_date = $("#assign_end_date").val();
    var userid = $("#assign_user_id").val();
    var task_id = $('#assign_task').val();
    var hour = $("#assign_hours").val();
    if(start_date && end_date && hour && userid && task_id){
        closeLoader();
        $.ajax({
            url: "/check-task-availability",
            data: {
                'userid': userid,
                'task_id': task_id,
                'hour': hour,
                'start_date': start_date,
                'end_date': end_date
            },
            type: 'POST',
            success: function(response) {
                closeLoader();
               if(response.data){
                    $("#task_estimate_alert").html(response.data);
               }
               if(response.status){
                    $('#assign_days_btn').prop('disabled', false);
                }else{
                    $('#assign_days_btn').prop('disabled', true);
                }
            }
        });
        
    }
}

function AssignDaysModal(user_id,unassign_hour) {
    var sub_task_id = $('input[type="radio"]:checked').val();
    var parent_id = $('input[type="radio"]:checked').attr('data-parent_id');
    $("#assign_days_btn").css('display','block');
    $(".assign_days_field").css('display','block');
    $("#assign_day_btn").css('display','none');
    if(parent_id){
        $("#assign_task").val(parent_id);
        chooseAssignTask(sub_task_id);
        
    }else{
        $("#assign_task").val(sub_task_id);
    }
    $("#assign_user_id").val(user_id);
    $("#unassign_hours").val(unassign_hour);
    var start_date = $('input[type="radio"]:checked').data('next_start_date');
    $("#assign_start_date").val(start_date);

    var end_date = $('input[type="radio"]:checked').data('next_end_date');
    $("#assign_end_date").val(end_date);
    resourceAssignHour();
    $("#task_estimate_alert").html('');
    $("#assignDaysModal").modal('show');
}

function resourceAssignHour(){
    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    $('#assign_start_date').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: tomorrow,
    }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#assign_end_date').datepicker('setStartDate', minDate);
    });
    $('#assign_end_date').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: $('#assign_start_date').datepicker('getDate')
    }).on('changeDate', function(selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#assign_start_date').datepicker('setEndDate', maxDate);
    });
    $('.chosen-select').chosen({
        width:"100%"
    });
}

function chooseAssignTask(subtask_id=0){
    var task_id=$("#assign_task").val();
    if(task_id){
        var start_date = $("#assign_task option:selected").data("next_start_date");
        var end_date = $("#assign_task option:selected").data("next_end_date");
        $("#assign_start_date").val(start_date);
        $("#assign_end_date").val(end_date);
        openLoader();
        $.ajax({
            url: "/fetch-subtasks",
            data: {
                'task_id': task_id,
                'subtask_id': subtask_id
            },
            type: 'POST',
            success: function(response) {
                closeLoader();
                $('#choose'+task_id).prop('checked', true);
                $("#assign_subtask").html(response);
                $("#assign_subtask").trigger("chosen:updated");
            },
            error: function(error) {
                closeLoader();
            }
        });
    }else{
        $('input[name="choose_task"]').prop('checked', false);
    }
    checkTaskAvailability();
}

function chooseAssignSubTask(){
    var task_id=$("#assign_task").val();
    var subtask_id=$("#assign_subtask").val();
    if(subtask_id){
        var start_date = $("#assign_subtask option:selected").data("next_start_date");
        var end_date = $("#assign_subtask option:selected").data("next_end_date");
        $("#assign_start_date").val(start_date);
        $("#assign_end_date").val(end_date);
        collapsebox(task_id);
        $('#choose'+subtask_id).prop('checked', true);
    }else{
        collapsebox(task_id)
        $('#choose'+task_id).prop('checked', true);
    }
    checkTaskAvailability();
}

function resetResources() {
    fetchTasks();
}

$(document).on('click', '.deleteconfirm', function() {
    $("#delete_task_hour").modal('show');
    var task_id = $(this).attr('data-task_id');
    var user_id = $(this).attr('data-user_id');
    var date = $(this).attr('data-date');
    $("#delete_task_id").val(task_id);
    $("#delete_user_id").val(user_id);
    $("#delete_assign_date").val(date);
});
$(document).on('click', '.deleteaction', function() {
    var choosed_task_id = $("#delete_task_id").val();
    var choosed_user_id = $("#delete_user_id").val();
    var choosed_date = $("#delete_assign_date").val();
    openLoader();
    $.ajax({
        url: "/delete-task-hours",
        data: {
            'task_id': choosed_task_id,
            'user_id': choosed_user_id,
            'date': choosed_date,
        },
        type: 'POST',
        success: function(response) {
            closeLoader();
            toastr.success('Task hour has been removed successfully.', 'Success');
            $("#taskModal").modal('hide');
            $("#delete_task_hour").modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            fetchResources()
        },
        error: function(error) {
            closeLoader();
            toastr.warning('Sorry, Please reload this page.', 'Failed');
        }
    });
});

$(document).on('click', '.add-project-user', function() {
    toasterOption();
    var resources = $('#select_resources').val();
    var projectId = $('#current_project_id').val();
    var ajaxUrl = '/gantt-project-update';
    $.ajax({
        method: 'POST',
        data: {
            'type': 'resource',
            'resources': resources,
            'project_id': projectId
        },
        url: ajaxUrl,
        success: function(response) {
            $('#project-assign-user').html(response);
            fetchResources();
            toastr.success('Project users has been updated successfully.', 'Updated');
        },
        error: function(error) {
        }
    });
});

function fetchProjectUsers(){
    $.ajax({
        method: 'POST',
        data: {
            'type': 'resource',
            'project_id': $("#project_id").val()
        },
        url: '/gantt-project-users',
        success: function(response) {
            $('#project-assign-user').html(response);
        },
        error: function(error) {
        }
    });
}