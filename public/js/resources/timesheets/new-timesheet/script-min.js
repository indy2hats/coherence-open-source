$(document).ready(function() {
    $(document).on('change', '#projectId,#userId,#clientId,#days, #userType, #sessionType, #projectCategory', function() {
        loadData();
    });
    $(document).on('click', '.month', function() {
        loadData();
    });
    $(document).on('click', '.applyBtn ', function() {
        $('#date').val('');
        loadData();
    });
});



function inputsLoader() {
    $('.chosen-select').chosen({
        width: "100%"
    });
    $('.datepicker').datepicker({
        startView: 1,
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        format: "M / yyyy",
        viewMode: "months",
        minViewMode: "months"
    });
    $('input[name="daterange"]').daterangepicker({
        opens: 'left',
        locale: {
            format: 'DD/MM/YYYY',
        },
        autoUpdateInput: false,
    });
    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        loadData();
    });

    var date = $('#date').val();
    if (date != '')
        $('#daterange').val('');
    $('.dataproject').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            { extend: 'copy' },
            { extend: 'csv' },
            { extend: 'excel', title: 'Hours Entered for ' + $('#projectId option:selected').text() },
            { extend: 'pdf', title: 'Hours Entered for ' + $('#projectId option:selected').text() },
            {
                extend: 'print',
                customize: function(win) {
                    $(win.document.body)
                        .css('font-size', '10pt')
                        .prepend(
                            '<div><h2>Hours Entered for ' + $('#projectId option:selected').text() + '</h2></div>'
                        );
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ]
    });
}

inputsLoader();

function loadData(page = 1) {
    $('.alert-start').addClass('hidden');
    $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
    var dateRange = $('#daterange').val();
    var projectId = $('#projectId').val();
    var userId = $('#userId').val();
    var dayType = $('#days').val();
    var clientId = $('#clientId').val();
    var date = $('.datepicker').val();
    var userType = $('#userType').val();
    var sessionType =  $('#sessionType').val();
    var projectCategory =  $('#projectCategory').val();
    $.ajax({
        type: 'POST',
        url: '/project-new-daterange-search',
        dataType: 'json',
        data: {
            'daterange': dateRange,
            'projectId': projectId,
            'userId': userId,
            'clientId': clientId,
            'date': date,
            'days' : dayType,
            'userType' : userType,
            'sessionType' : sessionType,
            'projectCategory' : projectCategory,
        },
        success: function(response) {
            $('.overlay').remove();
            $('.list').html(response.data);
            inputsLoader();
        }
    });
}

// Handle pagination click event
$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    loadData(page);
    $('html, body').animate({ scrollTop: 0 }, 'slow');
});

$(document).on('click', '.reset', function (e) {

    var currentDate = new Date();
    var month = currentDate.toLocaleString('default', { month: 'short' });
    var year = currentDate.getFullYear();
    var formattedDate = month +' / '+ year;

    $('#daterange').val('');
    $('#projectId').val('').trigger('chosen:updated');
    $('#userId').val([]).trigger('chosen:updated');
    $('#days').val('').trigger('chosen:updated');
    $('#clientId').val('').trigger('chosen:updated');
    $('#date').val(formattedDate);
    $('#userType').val('').trigger('chosen:updated');
    $('#sessionType').val('').trigger('chosen:updated');
    $('#projectCategory').val('').trigger('chosen:updated');
    loadData();
});

$(document).on('click', '#export-timesheet-csv', function(e) {
        e.preventDefault();
        openLoader();
        var dateRange = $('#daterange').val();
        var projectId = $('#projectId').val();
        var userId = $('#userId').val();
        var dayType = $('#days').val();
        var clientId = $('#clientId').val();
        var date = $('.datepicker').val();
        var userType = $('#userType').val();
        var sessionType =  $('#sessionType').val();
        var projectCategory =  $('#projectCategory').val();
        $.ajax({
                type: 'POST',
                url: '/export-timesheet',
                data: {
                    'daterange': dateRange,
                    'projectId': projectId,
                    'userId': userId,
                    'clientId': clientId,
                    'date': date,
                    'days' : dayType,
                    'userType' : userType,
                    'sessionType' : sessionType,
                    'projectCategory' : projectCategory,
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    var filename = 'Timesheet.xlsx';
                    var blob = new Blob([response], { type: 'application/octet-stream' });
                    var url = window.URL.createObjectURL(blob);
                    var link = document.createElement('a');
                    link.href = url;
                    link.download = filename;
                    link.click();
                    closeLoader();
                },
                error: function(xhr, status, error) {
                    closeLoader();
                }
        });
    });