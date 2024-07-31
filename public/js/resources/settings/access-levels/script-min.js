
    /** 
    * Create role form - submit buttom action
    */
    $('.create-role').click(function(e) {
        e.preventDefault();
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $('#add_role').modal('hide');
        $.ajax({
            type: 'POST',
            url: $('#add_role_form').attr('action'),
            data: $('#add_role_form').serialize(),
            success: function(res) {
                $('.overlay').remove();
                $('#add_role_form').trigger('reset');
                $("#level_container").html(res.data);
                toastr.success(res.message,'Created');
            },
            error: function(error) {
                $('.overlay').remove();
                $('#add_role').modal('show');
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        });
    });

    /** 
    * Create permission form - submit buttom action
    */
    $('.create-permission').click(function(e) {
        e.preventDefault();
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $('#add_permission').modal('hide');
        $.ajax({
            type: 'POST',
            url: $('#add_permission_form').attr('action'),
            data: $('#add_permission_form').serialize(),
            success: function(res) {
                $('.overlay').remove();
                $('#add_permission_form').trigger('reset');
                $("#level_container").html(res.data);
                toastr.success(res.message,'Created');
            },
            error: function(error) {
                $('.overlay').remove();
                $('#add_permission').modal('show');
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        });
    });

    /**
    * Save access levels - submit button action
    */
    $(document).on('click','.save-access-levels',function(e) {
        e.preventDefault();
        
        
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        
        $.ajax({
            type: 'POST',
            url: $('#access_levels_form').attr('action'),
            data: $('#access_levels_form').serialize(),
            success: function(res) {
                $('.overlay').remove();
                toastr.success(res.message);
                $("#level_container").html(res.data);
            },
            error: function(error) {
                $('.overlay').remove();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        })
    });

    /**
    * Adding role id to hidden text field while clicking delete
    */
    $(document).on('click', '.delete-role', function() {
        var deleteRoleId = $(this).data('id');
        $('#delete_role #delete_role_id').val(deleteRoleId);
    });
    
    /**
    * Role delete model - delete button action 
    */
    $(document).on('click', '#delete_role .continue-btn', function() {
        var deleteRoleId = $('#delete_role #delete_role_id').val();

        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $('#delete_role').modal('hide');

        $.ajax({
            type: 'POST',
            url: '/access-level-role-delete',
            data: {role_id : deleteRoleId},
            success: function(res) {
                $('.overlay').remove();
                if (res.error) {
                    toastr.error(res.error);
                } else {
                    toastr.success(res.message);
                    $("#level_container").html(res.data);
                }
            },
            error: function(error) {
                $('.overlay').remove();
                console.log(error);
            }
        });
    });

    /**
    * Removing validation errors and reset form on model window close
    */
    $('#add_holiday').on('hidden.bs.modal', function() {
        $(this).find('.text-danger').html('');
        $('#add_role_form').trigger('reset');
    });

