
	$('.status-type-table').dataTable({'ordering':false});
	$(document).on('click', '.create-status', function(e) {
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
                    $('#add_status_type').modal('hide');
                     closeLoader();
                     $('.status-list').html(response.data);
                    toastr.success(response.message, 'Created');
                    $('.status-type-table').dataTable({'ordering':false});
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

	$(document).on('click', '.edit-button', function(e) {
            e.preventDefault();
            openLoader();
            var typeId = $(this).data('id');
            var editUrl = "/status-types/" + typeId + '/edit';

            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $('#edit_type').html(data);
                    closeLoader();
                    $("#edit_type").modal('show');
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
                    $('#edit_type').modal('hide');
                    closeLoader();
                    $('.status-list').html(response.data);
                    toastr.success(response.message, 'Updated');
                    $('.status-type-table').dataTable({'ordering':false});
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

        $(document).on('click', '.delete_type_onclick', function() {
            var deleteId = $(this).data('id');
            $('#delete_type #delete_type_id').val(deleteId);
        });

        $(document).on('click', '#delete_type .continue-btn', function() {
            toasterOption();
            openLoader();
            var deleteId = $('#delete_type #delete_type_id').val();
            var deleteUrl = "/status-types/" + deleteId;
            $.ajax({
                type: 'DELETE',
                url: deleteUrl,
                data: {},
                success: function(response) {
                     $('#delete_type').modal('hide');
                    closeLoader();
                    $('.status-list').html(response.data);
                    toastr.success(response.message, 'Deleted');
                    $('.status-type-table').dataTable({'ordering':false});
                },
                error: function(error) {
                }
            });
        });


         /**
         * Removing validation errors and reset form on model window close
         */
        $('#add_status_type').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_form').trigger('reset');
        });
