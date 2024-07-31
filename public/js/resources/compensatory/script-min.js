
$('#data_3 .input-group.date').datepicker({
    startView: 2,
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    defaultDate: new Date()
});
inputsLoader();
function inputsLoader() {

    $('.datetimepicker').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true
    });


    $('.summernote').summernote();

    $('.dataTable').dataTable();
}

$(document).on('submit', '#add_form', function (e) {
    $('.text-danger').html('');
    e.preventDefault();
    openLoader();
    $.ajax({
        type: 'POST',
        url: $('#add_form').attr('action'),
        data: $('#add_form').serialize(),
        success: function (response) {
            $('#add_form').find('.text-danger').html('');
            $('#add_form').trigger('reset');
            $('#create_new').modal('hide');
            toastr.success('Added Successfully', 'Saved');
            $('.year-search').trigger('change');
        },
        error: function (error) {
            closeLoader();
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function (field, error) {
                    $('#label_' + field).html(error);
                });
            }
        }
    });
});

$(document).on('click', '.delete-item', function () {
    var deleteitemId = $(this).data('id');
    $('#delete_item #delete_item_id').val(deleteitemId);
});

/**
* Delete model continue button action
*/
$(document).on('click', '#delete_item .continue-btn', function () {
    var deleteitemId = $('#delete_item #delete_item_id').val();
    openLoader();
    $('#delete_item').modal('hide');
    $.ajax({
        method: 'DELETE',
        url: '/compensations/' + deleteitemId,
        data: {},
        success: function (response) {
            toastr.success('Deleted Successfully', 'Deleted');
            $('.year-search').trigger('change');
        },
        error: function (error) {
            closeLoader();
            toastr.error('Something went wrong', 'Error');
        }
    });
});

$(document).on('change', '.year-search', function (e) {
    $('.field-error').html('');
    e.preventDefault();
    openLoader();
    $.ajax({
        type: 'POST',
        url: '/user-search',
        data: {
            'date': $(this).val()
        },
        success: function (response) {
            $('.main').html(response.data);
            inputsLoader();
            closeLoader();
        },
        error: function (error) {
            closeLoader();
            toastr.error('Something went wrong', 'Error');
        }
    });
});