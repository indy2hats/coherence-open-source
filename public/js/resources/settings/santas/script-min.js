
    $('.santa-table').dataTable({
        "iDisplayLength": 50
    });
    $('.chosen-select').chosen({
        width: "100%"
    });
	$(document).on('click', '.create-session', function(e) {
            toasterOption();
            e.preventDefault();
            $('.field-error').html('');
            openLoader();
            var data = new FormData($('#add_form')[0]);
            $.ajax({
                url: $('#add_form').attr('action'),
                data: data,
                type: 'POST',
                contentType: false,
                cache: false,
                processData:false,
                success: function(response) {
                    $('#add_session_type').modal('hide');
                     closeLoader();
                     $('.session-list').html(response.data);
                    toastr.success(response.message, 'Created');
                    $('.santa-table').dataTable({
                        "iDisplayLength": 50
                    });
                },
                error: function(error) {
                    closeLoader();
                    if (error.status == 422) {
                        if (error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(field, error) {
                                $('#add_form #label_' + field).html(error);
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
            var editUrl = "/santa-members/" + typeId + '/edit';

            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $('#edit_type').html(data);
                    closeLoader();
                    $("#edit_type").modal('show');
                    $('.chosen-select').chosen({
                        width: "100%"
                    });
                }
            });
        });

        $(document).on('click', '.update-type', function(e) {
            toasterOption();
            $('.field-error').html('');
            e.preventDefault();
            openLoader();
            var data = new FormData($('#edit_form')[0]);
            $.ajax({
                type: 'POST',
                url: $('#edit_form').attr('action'),
                data: data,
                contentType: false,
                cache: false,
                processData:false,
                success: function(response) {
                    $('#edit_type').modal('hide');
                    closeLoader();
                    $('.session-list').html(response.data);
                    toastr.success(response.message, 'Updated');
                    $('.santa-table').dataTable({
                        "iDisplayLength": 50
                    });
                },
                error: function(error) {
                    closeLoader();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#edit_form #label_' + field).html(error);
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
            var deleteUrl = "/santa-members/" + deleteId;
            $.ajax({
                type: 'DELETE',
                url: deleteUrl,
                data: {},
                success: function(response) {
                     $('#delete_type').modal('hide');
                    closeLoader();
                    $('.session-list').html(response.data);
                    toastr.success(response.message, 'Deleted');
                    $('.santa-table').dataTable();
                },
                error: function(error) {
                }
            });
        });


         /**
         * Removing validation errors and reset form on model window close
         */
        $('#add_session_type').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_form').trigger('reset');
        });
