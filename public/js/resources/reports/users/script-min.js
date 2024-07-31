
$('.userTable').DataTable({
    paging: false,

    info: false,

    ordering: false,

    responsive: true,

    dom: '<"html5buttons"B>lTfgitp',

    buttons: [

        { extend: 'copy' },

        { extend: 'csv' },

        { extend: 'excel', title: 'Hours Spent by Employees' },

        { extend: 'pdf', title: 'Hours Spent by Employees' },



        {
            extend: 'print',

            customize: function (win) {
                $(win.document.body)
                    .css('font-size', '10pt')
                    .prepend(
                        '<div><h2>Hours Spent by Employees</h2></div>'
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

$('.chosen-select').chosen({
    width: "208px"
});

$(function () {
    var table = $('.userAccountReportTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        ordering: false,
        ajax: {
            url: "/report/user-accounts",
            data: function (d) {
                d.status = $('#status').val(),
                    d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'role', name: 'role' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' }
        ]
    });

    $('#status').change(function () {
        table.draw();
    });

    $(document).on('click', '.disable_user_2fa', function () {
        var userId = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: '/disable-user-authentication',
            data: { 'id': userId },
            success: function (response) {
                closeLoader();
                if (response.status) {
                    toastr.success(response.message, 'Success');
                }
                else {
                    toastr.error(response.message, 'Failed');
                }
                table.draw();
            }
        });
    });

});


$(document).ready(function () {
    $.fn.serializeObject = function () {
        var obj = {};
        $.each(this.serializeArray(), function (i, o) {
            var n = o.name, v = o.value;

            obj[n] = obj[n] === undefined ? v
                : $.isArray(obj[n]) ? obj[n].concat(v)
                    : [obj[n], v];
        });
        return obj;
    };


    var data_table = $('.userLeaveReportTable').DataTable({
        "searching": false,
        processing: true,
        serverSide: true,
        order: [[3, 'desc']],
        "pageLength": 25,
        ajax: {
            url: "/report/get-user-leave-report",
            data: function (result) {
                result.filter = $('#leave-report-filter-form').serializeObject();
            }
        },
        columns: [
            { data: 'employeeName' },
            { data: 'totalPaidLeaves' },
            { data: 'totalLops' },
            { data: 'totalLeaves' },
        ],
    });

    $('#leave-report-filter-form').submit(function (e) {
        e.preventDefault();
        data_table.draw();
    });


    $('.leave-report-datepicker').datepicker({
        startView: 1,
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        endDate: "+1y",
    });

});

$(".leave-filter").on("change input", function () {
    $("#leave-report-filter-form").submit();
});






