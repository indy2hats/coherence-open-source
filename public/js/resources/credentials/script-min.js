
    $(document).ready(function() {
        inputsLoader();

        $(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                $('#create_credential').modal('hide');
            }
        });

        
        /** to  save on enter key */
        $(document).on('keyup','#type_id', function (event) {
            if (event.keyCode === 13) {
                  $('.create-new').click();
            }
        });

      

        /**
         * submit button action
         */
        $('.create-new').click(function(e) {
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var data = $('#create_credential_id').serialize();
            $.ajax({
                method: 'POST',
                url: $("#create_credential_id").attr('action'),
                data: data,
                success: function(response) {
                    $(".overlay").remove();
                    $('#create_credential').modal('hide');
                    $("#table").html(response.data);
                    toastr.success(response.message, 'Saved');
                    inputsLoader();
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
        $('#create_credential').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#create_credential_id').trigger('reset');
        });


        $(document).on('click', '.delete-credential-data', function() {
            var deletefileId = $(this).data('id');
            $('#delete_credential #delete_credential_id').val(deletefileId);
        });

        /**
         * Delete credential - button click action
         */

        $(document).on('click', '#delete_credential .continue-btn', function() {
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var deletefileId = $('#delete_credential #delete_credential_id').val();
            $('#delete_credential').modal('hide');
            $.ajax({
                type: 'DELETE',
                url: '/my-credentials/' + deletefileId ,
                data:{},
                success: function(response) {
                    $(".overlay").remove();
                    toastr.success(response.message, 'Deleted');
                    $("#table").html(response.data);
                    inputsLoader();
                }
            });
        });

        /** 
         * Loading edit  form with data to edit modal
         */
        $(document).on('click', '.edit-data', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            editUrl = '../../my-credentials/' + id + '/edit';
            $.ajax({
                method: 'GET',
                url: editUrl,
                data: {},
                success: function(response) {
                    $('#edit_credential').html(response);
                    $(".overlay").remove();
                    $('#edit_credential').modal('show');
                    $('.summernote').summernote();
                }
            });
        });

        /** to update on enter key */
        $(document).on('keyup','#edit_type_id', function (event) {
        if (event.keyCode === 13) {
          $('.update-data').click();
          }
        });

        /**
         * Update client form - submit button action
         */
        $(document).on('click', '.update-data', function(e) {
            $('.field-error').html('');
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                type: 'PATCH',
                url: $('#edit_credential_id').attr('action'),
                data: $('#edit_credential_id').serialize(), 
                success: function(response) {
                    $(".overlay").remove();
                    $('#edit_credential').modal('hide');
                    toastr.success(response.message, 'Updated');
                    $("#table").html(response.data);
                    inputsLoader();
                },
                error: function(error) {
                    $(".overlay").remove();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#label_edit_' + field).html(error);
                        });
                    }
                }
            });
        });


    });

    function inputsLoader() {
        $('.credentialsTable').dataTable();
        $('.summernote').summernote();
    }
