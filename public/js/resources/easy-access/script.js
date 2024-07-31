
	$(document).on('click', '.create-easy-access', function(e) {
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
                    $('#add_easy_access').modal('hide');
                     closeLoader();
                    toastr.success('Successfully added new item.', 'Added');
                     $('.list').html(response.data);
                     $('.floatingMenu').html(response.links);
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
            var name = $(this).data('name');
            var link = $(this).data('link');
            var id = $(this).data('id');
            $('#edit_form #item_id').val(id);
            $('#edit_form #edit_name').val(name);
            $('#edit_form #edit_link').val(link);
            $('#edit_item').modal('show');
            
        });

        $(document).on('click', '.update-item', function(e) {
            toasterOption();
            $('.field-error').html('');
            e.preventDefault();
            openLoader();
            $.ajax({
                type: 'POST',
                url: $('#edit_form').attr('action'),
                data: $('#edit_form').serialize(),
                success: function(response) {
                    $('#edit_item').modal('hide');
                    closeLoader();
                    toastr.success('Updated the Link', 'Updated');
                    $('.list').html(response.data);
                     $('.floatingMenu').html(response.links);
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

        $(document).on('click', '.delete_item_onclick', function() {
            var deleteId = $(this).data('id');
            $('#delete_item #delete_item_id').val(deleteId);
        });

        $(document).on('click', '#delete_item .continue-btn', function() {
            toasterOption();
            openLoader();
            var deleteId = $('#delete_item #delete_item_id').val();
            $.ajax({
                type: 'POST',
                url: '/delete-easy-access',
                data: {
                    'delete_item_id':deleteId
                },
                success: function(response) {
                     $('#delete_item').modal('hide');
                    closeLoader();
                    $('.list').html(response.data);
                    toastr.success('Deleted Successfully', 'Deleted');
                     $('.floatingMenu').html(response.links);
                },
                error: function(error) {
                }
            });
        });


         /**
         * Removing validation errors and reset form on model window close
         */
        $('#add_easy_access').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_form').trigger('reset');
        });
