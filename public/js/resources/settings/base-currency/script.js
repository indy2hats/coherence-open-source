$(document).on('click', '.change-modal', function() {
    var id = $(this).data('id');
    editUrl = '/base-currency/' + id + '/edit';
    openLoader();
    $.ajax({
        method: 'GET',
        url: editUrl,
        data: {},
        success: function(response) {
            closeLoader();
            $('#change_currency').html(response);
            $('#change_currency').modal('show');
            $('.chosen-select').chosen({
                width:'100%'
            });

        }
    });
});

$(document).on('click', '.change-currency', function(e) {
    $('.field-error').html('');
    e.preventDefault();
    openLoader();
    $.ajax({
        type: 'POST',
        url: $('#change_form').attr('action'),
        data: $('#change_form').serialize(),
        success: function(response) {
            closeLoader();
            $('#change_currency').modal('hide');
            toastr.success('Updated Base Currency Details', 'Updated');
            location.reload(); 
        }
    });
});