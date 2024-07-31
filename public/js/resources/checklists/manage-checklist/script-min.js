    $('.chosen-select').chosen({
        width:"100%"
    });
    $(document).on('click', '.add_item', function(e) {
            e.preventDefault();
            var itemId = $(this).data('item-id');
            $("#add_item #item_id").val(itemId);
        });
	$(document).on('click', '.create-item-list', function(e) {
            toasterOption();
            e.preventDefault();
            $('.field-error').html('');
            openLoader();
            var data = $('#add_form_list').serialize();
            $.ajax({
                url: $('#add_form_list').attr('action'),
                data: data,
                type: 'POST',
                success: function(response) {
                    $('#add_item_category').modal('hide');
                     closeLoader();
                     $('.list').html(response.data);
                    toastr.success("Added to the list. ", 'Added');
                },
                error: function(error) {
                    closeLoader();
                    if (error.status == 422) {
                        if (error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(field, error) {
                                $('#label_list_' + field).html(error);
                            });
                        }
                    }
                }
            });
        });

    $(document).on('click', '.create-item', function(e) {

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
                    $('#add_item').modal('hide');
                     closeLoader();
                     $('.list').html(response.data);
                    toastr.success(response.message, 'Created');
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
            var itemId = $(this).data('id');
            var item = $(this).data('title');
            $("#edit_item #edit_title").val(item);
            $("#edit_item #edit_item_id").val(itemId);
            closeLoader();
            $("#edit_item").modal('show');

        });

        $(document).on('click', '.update-item', function(e) {
            toasterOption();
            $('.field-error').html('');
            e.preventDefault();
            openLoader();
            $.ajax({
                type: 'POST',
                url: "/checklists/"+$("#edit_item #edit_item_id").val(),
                data: $('#edit_form').serialize(),
                success: function(response) {
                    $('#edit_item').modal('hide');
                    closeLoader();
                    $('.list').html(response.data);
                    toastr.success(response.message, 'Updated');
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
            var deleteUrl = "/checklists/" + deleteId;
            $.ajax({
                type: 'DELETE',
                url: deleteUrl,
                data: {},
                success: function(response) {
                     $('#delete_item').modal('hide');
                    closeLoader();
                    $('.list').html(response.data);
                    toastr.success(response.message, 'Deleted');
                },
                error: function(error) {
                }
            });
        });


         /**
         * Removing validation errors and reset form on model window close
         */
        $('#add_item').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_form').trigger('reset');
        });
        $('#add_item_category').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_form_list').trigger('reset');
        });

        var max_fields      = 20;
        var wrapper         = $(".append-new");
        var add_button      = $(".add-btn");
        
        var x = 1; 
        $(add_button).click(function(e){ 
            e.preventDefault();
            if(x < max_fields){ 
                x++; 
                $(wrapper).append('<div class="row" style="padding-top:10px"><div class="col-sm-11"><div class="input-group"><input type="text" class="form-control" type="text" name="items[]" id="item"> <span class="input-group-btn"> <button type="button" class="btn btn-primary remove_field">X</button> </span></div></div></div>'); 
            }
        });
        
        $(wrapper).on("click",".remove_field", function(e){ 
            e.preventDefault(); 
            $(this).parent().parent().parent().parent().remove(); x--;
        })


        var max_fields_items      = 20;
        var wrapper_items         = $(".append-new-items");
        var add_button_items      = $(".add-btn-items");
        
        var y = 1; 
        $(add_button_items).click(function(e){ 
            if($('#add_form #title').val() == ''){
                $('#label_title').html('Please add a checklist');
                return;
            }
            else{
               $('#label_title').html('');
            }
            e.preventDefault();
            if(y < max_fields_items){ 
                y++; 
                $(wrapper_items).append('<div class="row" style="padding-top:10px"><div class="col-sm-11"><div class="input-group"><input type="text" class="form-control" type="text" name="items[]" id="items"> <span class="input-group-btn"> <button type="button" class="btn btn-primary remove_field_items">X</button> </span></div></div></div>'); 
            }
        });
        
        $(wrapper_items).on("click",".remove_field_items", function(e){ 
            e.preventDefault(); 
            $(this).parent().parent().parent().parent().remove(); y--;
        })

        $(document).on('click', '.collapse-link-user', function() {
            var ibox = $(this).closest('div.ibox');
              var button = $(this).find('i');
              var content = ibox.children('.ibox-content');
              content.slideToggle(200);
              button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
              ibox.toggleClass('').toggleClass('border-bottom');
              setTimeout(function() {
                ibox.resize();
                ibox.find('[id^=map-]').resize();
              }, 50);
        });

        $(document).on('click', '.share-with', function(e) {
            e.preventDefault();
            var itemId = $(this).data('id');
            $("#share_with #list_id").val(itemId);
            $('.chosen-select').chosen({
                width: "100%"
            });
        });

        $(document).on('click', '.share', function(e) {
            toasterOption();
            e.preventDefault();
            $('.field-error').html('');
            openLoader();
            $.ajax({
                url: '/share-checklist',
                data: {
                    'users':$('#data').val(),
                    'list_id':$("#share_with #list_id").val()
                },
                type: 'POST',
                success: function(response) {
                    $('#share_with').modal('hide');
                     closeLoader();
                    toastr.success("Shared the list. ", 'Shared');
                },
                error: function(error) {
                    closeLoader();
                    if (error.status == 422) {
                        if (error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(field, error) {
                                $('#label_share_' + field).html(error);
                            });
                        }
                    }
                }
            });
        });

        $(document).on('change', '#user_id', function(e) {
            $('.empty-list').hide();
            e.preventDefault();
            openLoader();
            $.ajax({
                url: '/search-checklist',
                data: {
                    'user_id':$('#user_id').val(),
                },
                type: 'POST',
                success: function(response) {
                    closeLoader();
                    $('.employee-list').html(response.data);
                },
                error: function(error) {
                    closeLoader();
                    toastr.error("Something went wrong.", 'Error');
                }
            });
        });


