
    jQuery(document).ready(function() {
        loadInputs();
    });
    function loadInputs() {
        $('.chosen-select').chosen({
            width: "100%"
        });

        $('#daterange').daterangepicker({
            opens: 'left',
              locale: {
                format: 'MMM DD, YYYY'
              },
            maxDate: moment(),
            ranges: {
               'Today': [moment(), moment()],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
               'This Year': [moment().startOf('year'), moment()],
               'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
            }
        });
        $("#daterange").val(""); 
        $("#daterange").attr("placeholder", "Select Date Range");
    }

    $(document).on("click", ".filter-reset", function (e) {
        e.preventDefault();
        openLoader();
        $(".filter").val("").trigger("chosen:updated");
        $(".task-filter").val("").trigger("chosen:updated");
        $("#daterange").val("");
        getTaskTimeData();
        closeLoader();
    });

    $(".filter").on('change', function(e){
        e.preventDefault();
        getTaskTimeUsers();
        getTaskTimeData();
    });

    $(".task-filter").on('change', function(e){
        e.preventDefault();
        getTaskTimeData();
    });

    function getTaskTimeUsers() {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '/get-task-time-users',
            data: {
                task_id : $("#search_task").val(), 
            },
            success: function(response) {
                closeLoader();
                loadAssignedUserList(response.data);
            }
        });
    }

    function loadAssignedUserList(assignees) {
        var selectElement = document.getElementById('search_assigned_user');
        selectElement.innerHTML = '';
        var defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Select User';
        selectElement.appendChild(defaultOption);
        
        if (assignees && assignees.length > 0) {
            assignees.forEach(function(assignee) {
                var option = document.createElement('option');
                option.value = assignee.id;
                option.text = assignee.first_name ;
                selectElement.appendChild(option);                
            });
        }
    
        $('.chosen-select').trigger('chosen:updated');
    }
    

    function getTaskTimeData()
    {
        openLoader();
        $("#task_content").html('');
        $.ajax({
            method: 'POST',
            url: '/task-time',
            data: $("#task-search-form").serialize(),
            success: function(response) {
                closeLoader();
                $("#task_content").html(response.data);
                initTable();
            }
        });
    }

    function initTable() {
        var table = $(".taskDataTable").DataTable({
            pageLength: 25  ,
            responsive: true,
            lengthMenu: [
                [25, 50, -1],
                [25, 50, "All"],
            ],
            dom: '<"html5buttons"B>lTfgitp',
            bInfo: false,
            buttons: [
                { extend: "copy" },
                { extend: "csv" },
                { extend: "excel", title: "Task Time Report" },
                { extend: "pdf", title: "Task Time Report" },
    
                {
                    extend: "print",
                    customize: function (win) {
                        $(win.document.body).addClass("white-bg");
                        $(win.document.body)
                            .css("font-size", "10px")
                            .prepend(
                                "<div><h2>Task Time Report</h2></div>"
                            );
    
                        $(win.document.body)
                            .find("table")
                            .addClass("compact")
                            .css("font-size", "inherit");
                    },
                },
            ],
        });
    }
    
