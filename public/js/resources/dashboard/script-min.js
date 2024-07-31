
var productive_data_table;
var upskilling_data_table;
var idle_data_table;
var onleave_data_table

loadCurrentTaskSession();

function loadCurrentTaskSession() {

    $.ajax({
        type: 'GET',
        url: '/check-task-session',
        data: {
            'task-id':$.cookie('running_task'),
        },
        success: function (response) {
            if(response.flag== true){
                if(response.status=='started'||response.status=='resume'){                    
                    $('#pause-task').removeClass('hidden');
                    $('#stop-task #timer-button').text('STOP');  
                    if (typeof $.cookie('running_task') === 'undefined'){
                        $.cookie('running_task',response.id, { expires: 1, path: '/' });
                        $.cookie('taskStartedBy', currentUser, { expires: 1, path: '/' });
                        $.cookie('timerRunning','true', { expires: 1, path: '/' });
                        loadCurrentTaskSession();
                    }
                }
            }
        }
    });
}


$(document).ready(function () {
    loadAllData();
});


$(document).on('change','#list-type',function() {
    $.cookie('list-type',$('#list-type').val(), { expires: 365, path: '/' });
    reloadAllData($('#list-type').val());    
});

function reloadAllData(listType) {
    reloadProductiveList(listType);
    reloadUpskillingList(listType);
    reloadIdleList(listType);
    reloadOnLeaveList(listType);
}

function loadAllData() {
    if ($.cookie('list-type')) {
        var listType = $.cookie('list-type');
        $("#list-type option[value='" + listType + "']").attr("selected", "selected");
    } else {
        var listType =  $('#list-type').val();
    }
      
    loadProductiveList(listType);
    loadUpskillingList(listType);
    loadIdleList(listType);
    loadOnLeaveList(listType);

    loadPieChart();
    var countdownrefesh = setInterval(function () {
        loadPieChart();
    }, 5 * 60 * 1000); 
    loadOverDueProjects();
    loadOverDueTasks();
}

function loadProductiveList(listType) {
    productive_data_table = $('.dataTableProductiveList').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        "pageLength": 25,
        ajax: {
            url: "/get-productive-users",
            data: { 'type': listType },
            "error": function(reason) {
                window.location.reload();
            }
        },
        columns: [
            { data: 'name' },
            { data: 'project' },
            { data: 'task' },
            { data: 'jiraId' },
            { data: 'estimatedTime' },
            { data: 'timeSpent' },
            { data: 'totalTimeSpent' },
            { data: 'deadline' },            
        ],
        "drawCallback": function() {
            getTimeTaken();
        }
    });

    var countdownrefesh = setInterval(function () {
        productive_data_table.draw();
    }, 5 * 60 * 1000); 
}

function loadUpskillingList(listType) {
    upskilling_data_table = $('.dataTableUpSkillingList').DataTable({
        processing: true,
        serverSide: true,
        "columnDefs": [ {
            "targets": 7,
            "orderable": false
        }],
        order: [[0, 'desc']],
        "pageLength": 25,
        ajax: {
            url: "/get-upskilling-users",
            data: { 'type': listType },
            "error": function(reason) {
                window.location.reload();
            }
        },
        columns: [
            { data: 'name' },
            { data: 'project' },
            { data: 'task' },
            { data: 'estimatedTime' },
            { data: 'timeSpent' },
            { data: 'totalTimeSpent' },
            { data: 'deadline' },
            { data: 'action' },
        ],
        "drawCallback": function() {
            getTimeTaken();
        }
    });

    var countdownrefesh = setInterval(function () {
        upskilling_data_table.draw();
    }, 5 * 60 * 1000); 
    
}

function loadIdleList(listType) {
    idle_data_table = $('.dataTableIdleList').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        "pageLength": 25,
        ajax: {
            url: "/get-idle-users",
            data: { 'type': listType },
            "error": function(reason) {
                window.location.reload();
            }
        },
        columns: [
            { data: 'name' },
        
        ],
    
    });
    var countdownrefesh = setInterval(function () {
        idle_data_table.draw();
    }, 5 * 60 * 1000);    
}

function loadOnLeaveList(listType) {
    onleave_data_table = $('.dataTableOnLeaveList').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        "pageLength": 25,
        ajax: {
            url: "/get-onleave-users",
            data: { 'type': listType },
            "error": function(reason) {
                    window.location.reload();
            }
        },
        columns: [
            { data: 'name' },
            { data: 'type' },
            { data: 'session' },
        
        ]
    });
    var countdownrefesh = setInterval(function () {
        onleave_data_table.draw();
    }, 5 * 60 * 1000);  
        
}

function reloadProductiveList(listType) {
    if(productive_data_table) {
        productive_data_table.destroy();
    }
    loadProductiveList(listType);
}

function reloadUpskillingList(listType) {
    if(upskilling_data_table) {
        upskilling_data_table.destroy();
    }
    loadUpskillingList(listType);
}

function reloadIdleList(listType) {
    if(idle_data_table) {
        idle_data_table.destroy();
    }
    loadIdleList(listType);
}

function reloadOnLeaveList(listType) {
    if(onleave_data_table) {
        onleave_data_table.destroy();
    }
    loadOnLeaveList(listType);
}

function getTimeTaken() {
    $(".time-taken").each(function() {
        var count = $(this).data('count');
        var diff = $(this).data('total');
        var timer = $(this).children('span');
        if (count > 0) {

            $.each($(this).data('starts').split(','), function(index, value) { 
                diff = diff + (( new Date() - new Date(value) ) / 1000 / 60) ;
            });

            var seconds_left = diff*60;
            var hours = 0;

            var countdownrefesh = setInterval(function () {

                // Add one to seconds
                seconds_left = seconds_left + count;

                hours_left = hours*3600+seconds_left;

                hours = parseInt(hours_left / 3600);
                seconds_left = seconds_left % 3600;

                minutes = parseInt(seconds_left / 60);

                //minutes = parseInt(minutes/60*100);

                t = hours + "h " + minutes + "m";
                timer.html(t)

            }, 1000);
        }
        
    });

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


function loadPieChart() {
    $.ajax({
        type: "POST",
        url: "/get-pie-chart-data",
        data: "{}",
        contentType: "application/json",
        dataType: "json",
        async: "true",
        cache: "false",
        success: function (result) {
            OnSuccess(result.data);
        },
        error: function (xhr, status, error) {
            window.location.reload();
        }
    });

}

function OnSuccess(data) {
    var activeCount = data['productiveUsers'];
    var upskillingCount = data['upskillingUsers'];
    var idleCount = data['idleUsers'];
    var leaveCount = data['onLeaveUsers'];
    c3.generate({
        bindto: '#pie',
        data: {
            columns: [
                ['Idle', idleCount ],
                ['Upskilling', upskillingCount ],
                ['Productive', activeCount ],
                ['Leave', leaveCount ]
            ],
            colors:{
                    Idle: '#ea3f59',
                    Upskilling: '#403dce',
                    Productive: '#23af59',
                    Leave: '#63abab'
            },
            type: 'pie'
        }
    });
}

function loadOverDueProjects()
{
    var project_data_table = $('.dataTableOverdueProjectList').DataTable({
        processing: true,
        serverSide: true,
        "columnDefs": [ {
            "targets": 2,
            "orderable": false
        }],
        order: [[3, 'desc']],
        "pageLength": 10,
        ajax: {
            url: "/get-overdue-projects",
            "error": function(reason) {
                    window.location.reload();
            }
        },
        columns: [
            { data: 'projectName' },
            { data: 'projectClientName' },
            { data: 'projectUsers' },
            { data: 'projectEndDate' },
        
        ],
    
    });
   
}

function loadOverDueTasks()
{
    var project_data_table = $('.dataTableOverdueTaskList').DataTable({
        processing: true,
        serverSide: true,
        "columnDefs": [ {
            "targets": 2,
            "orderable": false
        }],
        order: [[3, 'desc']],
        "pageLength": 10,
        ajax: {
            url: "/get-overdue-tasks",
            "error": function(reason) {
                    window.location.reload();
            }
        },
        columns: [
            { data: 'taskTitle' },
            { data: 'taskProjectName' },
            { data: 'taskUsers' },
            { data: 'taskEndDate' },
        
        ],
    
    });
   
}

$(document).on('click','.send-alert',function() {
    $.ajax({
            type: 'POST',
            url: '/send-timer-notification',
            data:{
                'user_id': $(this).attr('data-id')
            },
            success: function(response) {
                toastr.success(response.message, 'Sent');
            }
        });
});

$("[data-toggle='tooltip']").tooltip({
'selector': '',
'container':'body'
});

$('#data_3 .input-group.date').datepicker({
            startView: 2,
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            endDate: 'y',
            defaultDate: new Date()
        });

load();

$(document).on('change', '#date-chart', function() {
    load();
});

$(document).on('click', '.cache-clear', function() {
    $.ajax({
        method: 'POST',
        url: '/clear-chart-cache',
        data:{'date': $('#date-chart').val()},
        success: function(response) {
            toastr.success(response.success, 'Cleared!');
        }
    });
});

function load() {
    $('.income-chart').css('opacity',0.3);
    $.ajax({
        method: 'POST',
        url: '/load-chart-dashboard',
        data: {
            'date': $('#date-chart').val()
        },
        success: function(response) {
            $('.income-chart').css('opacity',1);
            if(response.error) {
                $('.income-chart').empty();
                $('.income-chart').append('<div style="color:red;">'+response.error+'</div>');
            } else {
                pageLoad(response.expense, response.year,parseInt(response.year)+1,response.months, response.baseCurrency);
            }
        },
 
    });
}

    function pageLoad(setData2, year,year2,months,baseCurrency) {
        $('.income-chart').empty();
        $('.income-chart').append('<div><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px none; height: 0px; margin: 0px; position: absolute; inset: 0px;"></iframe><canvas id="barChart" height="332" style="display: block; width: 570px; height: 266px;" width="700"></canvas></div>');
        var barData = {
        labels: months,
        datasets: [
            {
                label: "Expense",
                backgroundColor: '#dedede',
                borderColor: "rgb(26,179,148)",
                pointBackgroundColor: "rgb(26,179,148)",
                pointBorderColor: "#fff",
                data: setData2
            }
        ]
    };



    var barOptions = {
        responsive: true,
        scales: {
            yAxes: [{
                scaleLabel: {
                display: true,
                labelString: baseCurrency
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                var value = tooltipItem.yLabel;
                return datasetLabel + ': ' + value.toFixed(2) + ' ' + baseCurrency;
                }
            }
        }
    };

    var ctx2 = document.getElementById("barChart").getContext("2d");
    new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});

}




