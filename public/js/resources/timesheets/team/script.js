var teamTimesheetDatatable = $('.teamTimesheetTable').dataTable({
    "order": [
        [0, "asc"]
    ],
    "columnDefs": [{
        "orderable": false,
        "targets": 3
    }]
});

$(document).ready(function() {
    loadFilter();
});

function inputLoader(){
    $('.teamTimesheetTable').dataTable({
        "order": [
            [0, "asc"]
        ],
        "columnDefs": [{
            "orderable": false,
            "targets": 3
        }]
    });   
    $('#user_id').trigger('chosen:updated');
}

function loadFilter() {
    $('.timesheet-datepicker').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true
    });

    $('.chosen-select').chosen({
        width: "100%"
    });
}


$('#add_team').on('hidden.bs.modal', function() {
    $(this).find('.text-danger').html('');
    $('#add_team_form').trigger('reset');
});

$(document).on('click', '.delete-team-employee', function(e) {
    var teamId = $(this).attr('data-id');
    Swal.fire({
        title: 'Are you sure you want to remove this employee from your team ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Remove!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "/team/" + teamId,
                type: 'DELETE',
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content')
                },
                "success": function(response) {
                    toastr.success(response.message);
                    $("#reportees option[value='" + response.user + "']").remove();
                    $('#reportees').trigger('chosen:updated');
                    $(".list").html(response.data);
                    
                    inputLoader();
                    loadFilter();                    
                }
            });
        }
    });
});

$(document).on('change', '#date, #user_id', function(e) {
    $('#my-team-timesheet-filter-form').submit();
});

$(document).on('click', '.add-team', function(e) {
    $('.field-error').html('');
    e.preventDefault();
    $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
    var data = new FormData($('#add-team-form')[0]);
    $.ajax({
        type: 'POST',
        url:  $('#add-team-form').attr('action'),
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function(response) {
            $(".overlay").remove();
            $('#add_team').modal('hide');
            toastr.success(response.message, 'Added');

            reportees = $('#reportees').val();
            reportees.forEach(function(value) {
                $("#reportees option[value='" + value + "']").toggle();
            });

            $(".list").html(response.data);

            inputLoader();  
            loadFilter();
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