
    jQuery(document).ready(function() {

        $('.dataTable').DataTable();

        $(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                $('#create_sub_task').modal('hide');
            }
        });

        /** to update on enter key */
        $(document).on('keyup', '#subtask_title_id', function(event) {
            if (event.keyCode === 13) {
                $('.create-sub_task').click();
            }
        });

        $(document).on('keyup', '#subtask-estimated_time', function(event) {
            if (event.keyCode === 13) {
                $('.create-sub_task').click();
            }
        });

        $(document).on('keyup', '#subtask_url', function(event) {
            if (event.keyCode === 13) {
                $('.create-sub_task').click();
            }
        });

        $('.create-sub-modal').click(function(e) {
            $('.datetimepicker').datepicker('setDate', new Date());
        });

        $(document).on('click', '.create-sub-task', function(e) {
            e.preventDefault();
            $('.field-error').html('');
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var data = new FormData($('#add_sub_task_form')[0]);
            $.ajax({
                url: "/create-sub-task",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                type: 'POST',
                success: function(response) {
                    removeOverlay();
                    $('#create_sub_task').modal('hide');
                    $(".modal-backdrop").remove();
                    toastr.success(response.message, 'Created');
                    //$('.list').html(response.data);
                    // reloadPage();
                    loadSubTask();
                },
                error: function(error) {
                    removeOverlay();
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
        $('#create_sub_task').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_sub_task_form').trigger('reset');
            $('#add_sub_task_form .summernote').summernote('reset');
            $(".chosen-select").val('').trigger('chosen:updated');
        });

        $(document).on('click', '.delete_sub_task_onclick', function() {
            var deleteTaskId = $(this).data('id');
            $('#delete_sub_task #delete_sub_task_id').val(deleteTaskId);
        });


    });
    inputsLoader();

    function removeOverlay() {
        $("body .overlay").each(function() {
            $(this).remove();
        });
    }

    function inputsLoader() {

        $('.chosen-select').chosen({
            width: "100%"
        });

        $('.datetimepicker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true
        });
        $('.summernote').summernote();
    }
