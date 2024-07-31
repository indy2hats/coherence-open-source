<script type="text/javascript">

$(document).ready(function () {
    momentInit();
    loadInputs();
    $('.chosen-select').chosen({width:"100%"});
    dataDisplayTypeFilterRestriction();
    $('#dateRange').on('change',function(event) {
        dataDisplayTypeFilterRestriction();
    });
});

function momentInit() {
    moment.locale('en', {
        week: {
            dow: 1
        }
    });
}

function dataDisplayTypeFilterRestriction() {
    var dataDisplayType = $('#dataDisplayType');
    var dateRange = $('#dateRange');
    resetOptions(dataDisplayType);

    var differenceInDays = getDifferenceInDays();
    var progressGraphViewCutoffs = {!! json_encode(config('general.progress_graph_view_cutoffs')) !!};

    if (differenceInDays > progressGraphViewCutoffs.year) {
        disableAndDeselectOption(dataDisplayType, 'day');
        disableAndDeselectOption(dataDisplayType, 'week');
        disableAndDeselectOption(dataDisplayType, 'month');
    } else if (differenceInDays > progressGraphViewCutoffs.month) {
        disableAndDeselectOption(dataDisplayType, 'day');
        disableAndDeselectOption(dataDisplayType, 'week');
    } else if (differenceInDays > progressGraphViewCutoffs.day) {
        disableAndDeselectOption(dataDisplayType, 'day');
    }
}

function resetOptions(selector) {
    selector.find('option').prop('disabled', false).trigger('chosen:updated');
}

function disableAndDeselectOption(selector, value) {
    selector.find(`option[value="${value}"]`).prop('disabled', true).trigger("chosen:updated");
    selector.find(`option[value="${value}"]`).prop('selected', false).trigger("chosen:updated");
}

function getDifferenceInDays() {
    var stratAndEndDates = getDates();
    var startDateStr = stratAndEndDates[0];
    var endDateStr = stratAndEndDates[1];

    var startDate = new Date(startDateStr.replace(/(\d{2})\/(\d{2})\/(\d{4})/, '$3-$2-$1'));
    var endDate = new Date(endDateStr.replace(/(\d{2})\/(\d{2})\/(\d{4})/, '$3-$2-$1'));

    var difference = endDate.getTime() - startDate.getTime();

    return difference / (1000 * 3600 * 24);
}

function getDates() {
    var $dateRange = $('#dateRange');
    var selectedDateRange = $dateRange.val();
    var dates = selectedDateRange.split(' - ');
    var startDateStr = dates[0];
    var endDateStr = dates[1];
    return [startDateStr, endDateStr];
}

function loadInputs() {
    var selectedRange = "{{ request()->input('dateRange') }}";
    var startDateOfWeek, endDateOfWeek;
    var dateFormat = 'DD/MM/YYYY';

    if (selectedRange) {
        var range = selectedRange.split(' - ');
        startDateOfWeek = moment(range[0],dateFormat);
        endDateOfWeek = moment(range[1],dateFormat);
    } else {
        startDateOfWeek = moment().startOf('week');
        endDateOfWeek = moment().endOf('week');
    }

    $('.weekpicker').daterangepicker({
        opens: 'left',
        locale: {
            format: "DD/MM/YYYY",
        },
        ranges: {
            'This Week': [moment().startOf('week'), moment().endOf('week')],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last 3 Months': [moment().subtract(3, 'months').startOf('month'), moment().endOf('month')],
            'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
        },
        startDate: startDateOfWeek,
        endDate: endDateOfWeek
    });
}

var weeks = {!! json_encode($weeks) !!};
var totalHours = {!! json_encode($timeSpent) !!};
var billableHours = {!! json_encode($billableHours) !!};
var projectName = "{{ $projectName }}";
var xAxesTitle = {!! json_encode($xAxisTitle) !!};

loadProjectBillabilityGraph(weeks, totalHours, billableHours, xAxesTitle);

function loadProjectBillabilityGraph(weeks, totalHours, billableHours, xAxesTitle) {

    $('.billability-time-chart').empty();
    $('.billability-time-chart').append('<div><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px none; height: 0px; margin: 0px; position: absolute; inset: 0px;"></iframe><canvas id="projectBarChart" height="332" style="display: block; width: 570px; height: 266px;" width="700"></canvas></div>');

    var lineData = {
        labels: weeks,
        datasets: [
            {
                label: "Total Hours",
                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                borderColor: 'rgba(255, 0, 0, 0.6)',
                pointBackgroundColor: 'rgba(255, 0, 0, .8)',
                pointBorderColor: '#fff',
                data: totalHours,
                fill: false,
                lineTension: 0
            },
            {
                label: "Billable Hours",
                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                borderColor: 'rgba(0, 0, 255, 0.6)',
                pointBackgroundColor: 'rgba(0, 0, 255, .8)',
                pointBorderColor: '#fff',
                data: billableHours,
                fill: false,
                lineTension: 0
            }
        ]
    };

    var lineOptions = {
        responsive: true,
        scales: {
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Hours'
                },
                ticks: {
                    beginAtZero: true
                }
            }],
            xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: xAxesTitle
                },
                ticks: {
                    autoSkip: false
                }
            }]
        },
        title: {
            display: true,
            text: projectName,
            fontSize: 18,
            fontColor: '#333'
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

var ctx = document.getElementById("projectBarChart").getContext("2d");
new Chart(ctx, { type: 'line', data: lineData, options: lineOptions });

}


$(document).on("click", ".reset-filter", function (e) {
    e.preventDefault();
    openLoader();
    $(".chosen-select").val("").trigger("chosen:updated");
    closeLoader();
});

$(document).on("click", ".search", function (e) {
    e.preventDefault();
    $('#dataDisplayType-error, #project-error').addClass('hide');

    var flag = true;

    if ($('#projectId').val() == "") {
        $('#project-error').removeClass('hide');
        flag = false;
    }

    if ($('#dataDisplayType').val() == "") {
        $('#dataDisplayType-error').removeClass('hide');
        flag = false;
    }

    if (!flag) {
        return false;
    }

    $("#project-progress-graph-form").submit();
});

</script>
