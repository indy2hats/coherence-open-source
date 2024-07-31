$('.chosen-select').chosen({
		width:"100%"
	});
	$('.datetimepicker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            format: "yyyy-mm-dd",
            autoclose: true
        });

    $(document).on('change', '#type', function() {
        if($(this).val() == 'Wish' || $(this).val() == 'Message'){
            $(".file-upload #file").trigger('reset');
            $(".file-upload").empty();
            $(".file-upload").append('<label>Upload <span class="required-label">*</span></label><input class="form-control" type="file" name="file" id="file"><div class="text-danger text-left field-error" id="label_file"></div>');
        }
        else if($(this).val() == 'Text'){
            $(".file-upload #file").trigger('reset');
            $(".file-upload").empty();
            $(".file-upload").append('<label>Content <span class="required-label">*</span></label><textarea class="form-control summernote" type="file" name="file" id="file" row="4"></textarea><div class="text-danger text-left field-error" id="label_file"></div>');
            $('.summernote').summernote();
        }
    });
	$(document).on('click', '.create-alert', function(e) {
            $('.field-error').html('');
            e.preventDefault();
            openLoader();
            var data = new FormData($('#add_form')[0]);
            $.ajax({
                type: 'POST',
                url:  $('#add_form').attr('action'),
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    closeLoader();
                    $('#add_alert').modal('hide');
                    toastr.success(response.message, 'Added');
                    $('.list').html(response.data);
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

	$(document).on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_form').trigger('reset');
        });

	$(document).on('click', '.delete-alert', function() {
            var deleteAlertId = $(this).data('id');
            $('#delete_alert #delete_alert_id').val(deleteAlertId);
        });

        /**
         * Delete alert - button click action
         */
        $(document).on('click', '#delete_alert .continue-btn', function() {
            var deleteAlertId = $('#delete_alert #delete_alert_id').val();
            deleteUrl = '/alerts/' + deleteAlertId;
            openLoader();
            $('#delete_alert').modal('hide');
            $.ajax({
                type: 'DELETE',
                url: deleteUrl,
                data: {},
                success: function(response) {
                    closeLoader();
                    toastr.success(response.message, 'Deleted');
                    $('.list').html(response.data);
                },
                error: function(error) {
                    closeLoader();
                    toastr.error("Something went wrong", 'Error');
                }
            });
        });