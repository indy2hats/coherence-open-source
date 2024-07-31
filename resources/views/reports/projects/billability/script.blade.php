<script type="text/javascript">

$(document).ready(function () {
    loadInputs();
    initTable();
    var pageType = "{{ request()->input('page-type') }}";
    showListGraph(pageType);
    $('.chosen-select').chosen({width:"100%"});
    $(document).on("click", ".search", function (e) {
        searchList(e);
    });
});

function searchList(e) {
    e.preventDefault();
    $("#project-billibility-form").submit();
}

$(document).on("click", ".reset-filter", function (e) {
    e.preventDefault();
    openLoader();
    localStorage.removeItem('selectedProjects');
    $(".project-filter").val("").trigger("chosen:updated");
    $(".session-filter").val("").trigger("chosen:updated");
    $(".client-filter").val("").trigger("chosen:updated");
    $(".saved-filter").val("").trigger("chosen:updated");
    loadInputs('reset');
    searchList(e);
    closeLoader();
});

function loadInputs(type=null) {
    var daterangeValue = "{{ request()->input('daterange') }}";
    var startDate, endDate;
    if (daterangeValue && daterangeValue !== '' && type != 'reset') {
        var range = daterangeValue.split(' - ');
        startDate = range[0];
        endDate = range[1];
    } else {
        startDate = new Date();
        startDate.setMonth(startDate.getMonth() - 1);
        endDate = new Date();
    }

    $('input[name="daterange"]').daterangepicker({
        opens: "left",
        locale: {
            format: "DD/MM/YYYY",
        },
        startDate: startDate,
        endDate: endDate
    });
   $('input[name="daterange"]').attr("placeholder", "Select Date Range");
}

function initTable() {
    var table = $(".listData").DataTable({
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
            { extend: "excel", title: "Project Billability Report" },
            { extend: "pdf", title: "Project Billability Report" },

            {
                extend: "print",
                customize: function (win) {
                    $(win.document.body).addClass("white-bg");
                    $(win.document.body)
                        .css("font-size", "10px")
                        .prepend(
                            "<div><h2>Project Billability Report</h2></div>"
                        );

                    $(win.document.body)
                        .find("table")
                        .addClass("compact")
                        .css("font-size", "inherit");
                },
            },
        ],
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api();
            var totalTimeSpent = updateTotalHours(api, 'time-spent');
            var totalBilledHours = updateTotalHours(api, 'billed-hours');
            updateTotalHours(api, 'non-billed-hours');
            updatePercentage(api, totalBilledHours, totalTimeSpent, 'percentage');

        },
    });
}

function updateTotalHours(api, className) {
    var totalMinutes = api.column('.' + className, { filter: 'applied' })
        .data()
        .reduce(function (acc, value) {
            var hours = 0;
            var minutes = 0;
            var match = value.match(/^(\d+)h?\s*(\d+)?m?$/);
            if (match) {
                hours = parseInt(match[1]);
                minutes = match[2] ? parseInt(match[2]) : 0;
            }
            return acc + hours * 60 + minutes;
        }, 0);
    var hours = Math.floor(totalMinutes / 60);
    var minutes = totalMinutes % 60;
    $(api.column('.' + className).footer()).html(hours + 'h ' + minutes + 'm');
    return totalMinutes ;
}

function updatePercentage(api, numerator, denominator, className) {
    var percentage = 0.00;
    if(denominator > 0) {
        percentage = (numerator / denominator) * 100;
    }
    $('.' + className).html(percentage.toFixed(2) + '%');
}

$(document).on('click', '.button-graph', function(e) {
    e.preventDefault();
    showListGraph()
});

function showListGraph(page=null) {
    if($('.button-graph').text() == 'Show List' || page == 'list' || page == '') {
        $('.project-content').show();
        $('.billability-chart').hide();
        $('.button-graph').text('Show Graph');
        $("#page-type").val("list");
    } else if ($('.button-graph').text() == 'Show Graph'  || page == 'graph') {
        $('.billability-chart').show();
        $('.project-content').hide();
        $('.button-graph').text('Show List');
        $("#page-type").val("graph");
        load();
    }
}

function load() {
    $('.billability-chart').css('opacity',0.3);
    $.ajax({
        method: 'POST',
        url: '{{ route("projectBillabilityGraph") }}',
        data: $('#project-billibility-form').serialize(),
        success: function(response) {
            $('.billability-chart').css('opacity',1);
            if(response.error) {
                $('.billability-chart').empty();
                $('.billability-chart').append('<div style="color:red;">'+response.error+'</div>');
            } else {
                loadProjectBillabilityGraph(response.projects, response.timeSpent, response.billedHours,response.nonBilledHours);
                loadProjectBillabilityPercentageGraph(response.projects, response.percentage);
            }
        },

    });
}

function loadProjectBillabilityGraph(projects,timeSpent, billedHours, nonBilledHours) {
        $('.billability-time-chart').empty();
        $('.billability-time-chart').append('<div><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px none; height: 0px; margin: 0px; position: absolute; inset: 0px;"></iframe><canvas id="projectBarChart" height="332" style="display: block; width: 570px; height: 266px;" width="700"></canvas></div>');
        var barData = {
        labels: projects,
        datasets: [
            {
                label: "Time Spent In Hours",
                backgroundColor: '#b5b8cf',
                borderColor: "rgb(26,179,148)",
                pointBackgroundColor: "rgb(26,179,148)",
                pointBorderColor: "#fff",
                data: timeSpent
            },
            {
                label: "Billable Hours",
                backgroundColor: '#a3e1d4',
                borderColor: "rgb(26,179,148)",
                pointBackgroundColor: "rgb(26,179,148)",
                pointBorderColor: "#fff",
                data: billedHours
            },
            {
                label: "Non Billed Hours",
                backgroundColor: '#dedede',
                borderColor: "rgb(26,179,148)",
                pointBackgroundColor: "rgb(26,179,148)",
                pointBorderColor: "#fff",
                data: nonBilledHours
            }
        ]
    };

    var barOptions = {
        responsive: true,
        scales: {
            yAxes: [{
                scaleLabel: {
                display: true,
                labelString: 'Hours'
                }
            }],
            xAxes: [{
                ticks: {
                    autoSkip: false,
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                var value = tooltipItem.yLabel;
                return datasetLabel + ': ' + value.toFixed(2);
                }
            }
        }
    };

    var ctx2 = document.getElementById("projectBarChart").getContext("2d");
    new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});
}


function loadProjectBillabilityPercentageGraph(projects,percentage) {
        $('.billability-percentage-chart').empty();
        $('.billability-percentage-chart').append('<div><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px none; height: 0px; margin: 0px; position: absolute; inset: 0px;"></iframe><canvas id="projectPercentageBarChart" height="332" style="display: block; width: 570px; height: 266px;" width="700"></canvas></div>');
        var barData = {
        labels: projects,
        datasets: [
            {
                label: "Percentage",
                backgroundColor: '#b5b8cf',
                borderColor: "rgb(26,179,148)",
                pointBackgroundColor: "rgb(26,179,148)",
                pointBorderColor: "#fff",
                data: percentage
            },
        ]
    };

    var barOptions = {
        responsive: true,
        scales: {
            yAxes: [{
                scaleLabel: {
                display: true,
                labelString: 'Percentage %'
                }
            }],
            xAxes: [{
                ticks: {
                    autoSkip: false,
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                var value = tooltipItem.yLabel;
                return datasetLabel + ': ' + value.toFixed(2) + '%';
                }
            }
        }
    };

    var ctx2 = document.getElementById("projectPercentageBarChart").getContext("2d");
    new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});
}

$(".save-filter").on('click', function(event) {
    event.preventDefault();
    clearSaveFilterErrorMessages();
    $('#filterName').val('');
});

$(".save-filter-form").on('click', function(event) {
    event.preventDefault();
    saveFilter();
});

function saveFilter() {
    clearSaveFilterErrorMessages();
    var filterName = $('#filterName').val();
    var filterReportName = "billability-report";
    var projectIds = $(".project-filter").val();
    var clinetIds = $(".client-filter").val();
    var sessionIds = $(".session-filter").val();
    if (filterName != "") {
        saveFilterAjax(filterName, filterReportName, projectIds, clinetIds, sessionIds)
    } else {
        $('#filterSaveError').text("Please enter the filter name.");
    }

}

function saveFilterAjax(filterName, filterReportName, projectIds, clinetIds, sessionIds) {
    $.ajax({
        method: 'POST',
        url: '{{ route("saveReportFilter") }}',
        data: { name: filterName, report_name: filterReportName, project_ids: projectIds, client_ids: clinetIds, session_type_ids: sessionIds },
        success: function(response) {
            $('#filterSaveSuccess').text(response.message);
            setTimeout(function() {
                $('#save-selected-filters').modal('hide');
            }, 1000);
            loadCreatedFilter(response.insertedFilterId);
        },
        error: function(jqXHR) {
            if (jqXHR.status === 422) {
                var errors = jqXHR.responseJSON.errors;
                $('#filterSaveError').text(errors.slug[0]);
            } else {
                console.log(jqXHR.statusText);
            }
        }
    });
}

function clearSaveFilterErrorMessages() {
    $('#filterSaveError').text('');
    $('#filterSaveSuccess').text('');
}

$('#savedFilter').on('change', function() {
    var savedFilterId = $(this).val();
    if (savedFilterId != "") {
        getSavedFilterData(savedFilterId);
    }
});

function getSavedFilterData(savedFilterId) {
    clearSelectedFilters();
    $.ajax({
        method: 'POST',
        url: '{{ route("getReportFilterData") }}',
        data: { savedFilterId },
        success: function(response) {
            updateFilter('.client-filter', response.client_ids, 'Select Client');
            updateFilter('.project-filter', response.project_ids, 'Select Project');
            updateFilter('.session-filter', response.session_type_ids, 'Select Session');
        },
    });
}

function updateFilter(selector, values, placeholder) {
    var $select = $(selector);
    if (values != null && values.length > 0) {
        $select.val(values).trigger('chosen:updated');
    }
}

function clearSelectedFilters() {
    $('.client-filter option').prop('selected', false).trigger('chosen:updated');
    $('.session-filter option').prop('selected', false).trigger('chosen:updated');
    $('.project-filter option').prop('selected', false).trigger('chosen:updated');
}

function addOptionButtons() {
    var chosenDropdown = $('.chosen-container .chosen-results li');
    chosenDropdown.each(function() {
        if (!$(this).find('.option-button').length) {
            var button = $('<i style="font-size:16px" class="fa fa-trash-o pull-right"></i>');
            $(this).append(button);
            button.on('click', function(event) {
                var selectedOption = $(this).closest('.chosen-container').siblings('select').find('option:selected');
                var selectedValue = selectedOption.val();
                deleteFilter(selectedValue);
            });
        }
    });
}

$('.saved-filter').on('chosen:showing_dropdown', function() {
    addOptionButtons();
});

function deleteFilter(selectedValue) {
    $('#delete_filter').modal('show');
    var confirmButton = $('#delete_filter').find('.continue-btn');
    confirmButton.off('click');
    confirmButton.on('click', function() {
        deleteFilterAjax(selectedValue);
        $('#delete_filter').modal('hide');
    });
}

function deleteFilterAjax(savedFilterId) {
    $.ajax({
        method: 'POST',
        url: '{{ route("deleteReportFilter") }}',
        data: { savedFilterId },
        success: function(response) {
            location.reload();
        },
    });
}

function loadCreatedFilter(insertedFilterId) {
    var filterReportName = "billability-report";
    $.ajax({
        method: 'POST',
        url: '{{ route("getReportFilters") }}',
        data: { filterReportName },
        success: function(response) {
            var $select = $('#savedFilter');
            $select.empty();
            $select.append('<option value=""></option>');
            $.each(response, function(key, value) {
                $select.append('<option value="' + value.id + '">' + value.name + '</option>');
            });
            $select.val(insertedFilterId).trigger('chosen:updated');
        },
    });
}

$('.project-filter, .client-filter, .session-filter').on('change', function() {
    $('.saved-filter').val('').trigger('chosen:updated');
});

$('.progress-graph').on('click', function() {
    var daterangeValue = $('#daterange').val();
    var projectId = $(this).attr('data-projectId');
    $('#progressGraphProjectId').val(projectId);
    $('#progressGraphDateRange').val(daterangeValue);
    $('#project-progress-graph-form').submit();
});

</script>
