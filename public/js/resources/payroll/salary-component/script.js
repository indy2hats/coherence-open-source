$(document).on('click', '.create-salary-component', function(e) {
    toasterOption();
    e.preventDefault();
    $('.field-error').html('');
    openLoader();
    var data = $('#add_form').serialize();
    $.ajax({
        url: $('#add_form').attr('action'),
        data: data,
        type: 'POST',
        success: function(response) {  
            $('#add-salary-component').modal('hide');
             closeLoader();    
            if(response.status==200){    
                $('.salary-component-list').html(response.data);
                toastr.success(response.message);
                $('.salary-component-table').DataTable({
                    info:false,
                    ordering:false,    
                });
            }
            else{           
            toastr.error(response.message);
            }
            
        },
        error: function(error) {
            closeLoader();
            if (error.status == 422) {
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        }
    });
});

$(document).on('click', '.edit-component-button', function(e) {
    e.preventDefault();
    openLoader();
    var typeId = $(this).data('id');  
    var editUrl = "/salary-component/" + typeId + '/edit';

    $.ajax({
        type: 'GET',
        url: editUrl,
        data: {},
        success: function(data) {
            $('#edit-component').html(data);
            closeLoader();
            $("#edit-component").modal('show');
        }
    });
});

$(document).on('click', '.update-type', function(e) {
    toasterOption();
    $('.field-error').html('');
    e.preventDefault();
    openLoader();
    $.ajax({
        type: 'POST',
        url: $('#edit_form').attr('action'),
        data: $('#edit_form').serialize(),
        success: function(response) {
            $('#edit-component').modal('hide');
            closeLoader();
            if(response.status==200){   
                $('.salary-component-list').html(response.data);
                toastr.success(response.message);
                $('.salary-component-table').DataTable({
                    info:false,
                    ordering:false,    
                });
            }
            else{           
                toastr.error(response.message);
            }
        },
        error: function(error) {
            closeLoader();
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function(field, error) {
                    $('#label_' + field).html(error);
                });
            }
        }
    });
});

$('.salary-component-table').DataTable({
    info:false,
    ordering:false,    
});

$(document).on('click', '.delete_component_onclick', function() {
    var deleteId = $(this).data('id');
    $('#delete_component #delete_component_id').val(deleteId);
});

$(document).on('click', '#delete_component .continue-btn', function() {
    toasterOption();
    openLoader();
    var deleteId = $('#delete_component #delete_component_id').val();
    var deleteUrl = "/salary-component/" + deleteId;
    $.ajax({
        type: 'DELETE',
        url: deleteUrl,
        data: {},
        success: function(response) {
            $('#delete_component').modal('hide');
            closeLoader(); 
            if(response.status==200){   
                $('.salary-component-list').html(response.data);
                toastr.success(response.message);
                $('.salary-component-table').DataTable({
                    info:false,
                    ordering:false,    
                });
            }
            else{           
                toastr.error(response.message);
            }
        },
        error: function(error) {
            closeLoader();
            toastr.error('Something went wrong');
        }
    });
});

$('#add-salary-component').on('hidden.bs.modal', function() {
    $(this).find('.text-danger').html('');
    $('#add_form').trigger('reset');
});

$('.chosen-select').chosen({
    width: "100%"
});