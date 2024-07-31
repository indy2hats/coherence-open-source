

    $(document).ready(function(){

        
        $(document).on('click', '.create-tag', function(e) {
            toasterOption();
            e.preventDefault();
            $('.field-error').html('');
            openLoader();
            var data = $('#add_tag_form').serialize();
            $.ajax({
                url: $('#add_tag_form').attr('action'),
                data: data,
                type: 'POST',
                success: function(response) {
                    $('#add_tag').modal('hide');
                    closeLoader();
                    toastr.success(response.message, 'Created');
                    if(($("#edit_task").data('bs.modal') || {}).isShown) {
                            $('#edit_task_form #tag').append('<option selected value="'+response.tag.slug+'">'+response.tag.title+'</option>').trigger("chosen:updated");
                    }
                    if(($("#create_sub_task").data('bs.modal') || {}).isShown) {
                            $('#add_sub_task_form #tag').append('<option selected value="'+response.tag.slug+'">'+response.tag.title+'</option>').trigger("chosen:updated");
                    }
                    if ($('#add_task_form #tag').length){
                        if(($("#create_task").data('bs.modal') || {}).isShown) {
                           $('#add_task_form #tag').append('<option selected value="'+response.tag.slug+'">'+response.tag.title+'</option>').trigger("chosen:updated"); 
                        } else {
                            $('#add_task_form #tag').append('<option value="'+response.tag.slug+'">'+response.tag.title+'</option>').trigger("chosen:updated"); 
                        }
                    }
                },
                error: function(error) {
                    closeLoader();
                    if (error.status == 422) {
                        if (error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(field, error) {
                                $('#add_tag_form #label_' + field).html(error);
                            });
                        }
                    }
                }
            });
        });

        $('#add_tag').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_tag_form').trigger('reset');
        });
    });
