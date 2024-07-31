
    $(document).ready(function() {
        inputsLoader();

        /**
         * Upload file - submit button action
         */
        $('.upload-file').click(function(e) {
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var data = new FormData($('#upload-file-form')[0]);
            $.ajax({
                method: 'POST',
                url: $('#upload-file-form').attr('action'),
                data: data,
                contentType: false,
                cache: false,
                processData:false,
                success: function(response) {
                    $(".overlay").remove();
                    $('#upload_file').modal('hide');
                    $("#table").html(response.data);
                    toastr.success(response.message, 'Uploaded');
                    $('#upload-file-form').trigger('reset');
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

        /**
         * Upload file - submit button action
         */
        $('.upload-link').click(function(e) {
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var data = new FormData($('#upload-link-form')[0]);
            $.ajax({
                method: 'POST',
                url: $('#upload-link-form').attr('action'),
                data: data,
                contentType: false,
                cache: false,
                processData:false,
                success: function(response) {
                    $(".overlay").remove();
                    $('#upload_link').modal('hide');
                    $("#table").html(response.data);
                    toasterOption();
                    toastr.success(response.message, 'Uploaded');
                    $('#upload-link-form').trigger('reset');
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


        /**
         * Removing validation errors and reset form on model window close
         */
        $('#upload_file').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            //$('#upload-file-form').trigger('reset');
        });
        /**
         * Removing validation errors and reset form on model window close
         */
        $('#upload_link').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            //$('#upload-link-form').trigger('reset');
        });


        $(document).on('click', '.delete-file-show', function() {
            var deletefileId = $(this).data('id');
            $('#delete_file #delete_file_id').val(deletefileId);
        });

        /**
         * Delete project - button click action
         */
        $(document).on('click', '#delete_file .continue-btn', function() {
            var deletefileId = $('#delete_file #delete_file_id').val();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#delete_file').modal('hide');
            $.ajax({
                type: 'DELETE',
                url: '/project-documents/' + deletefileId ,
                data:{},
                success: function(response) {
                    $(".overlay").remove();
                    toastr.success(response.message, 'Deleted');
                    $(".table").html(response.data);
                }
            });
        });


    });

    function inputsLoader() {
      
        $('.files').dataTable();
        $('.summernote').summernote();
    }
