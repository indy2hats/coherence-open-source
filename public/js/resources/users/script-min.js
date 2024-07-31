

     $(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                $('#add_employee').modal('hide');
                $('#edit_employee').modal('hide');
                $('#delete_employee').modal('hide');

            }
        });
    $(document).ready(function() {

        typeAhead();
        inputsLoader();
        
             /** to  save on enter key */
        $(document).on('keyup', '#first_name_id', function(event) {
            if (event.keyCode === 13) {
                $('.create_user').click();
            }
        });
        
        $(document).on('keyup', '#last_name_id', function(event) {
            if (event.keyCode === 13) {
                $('.create_user').click();
            }
        });

        $(document).on('keyup', '#email_id', function(event) {
            if (event.keyCode === 13) {
                $('.create_user').click();
            }
        });
        
        $(document).on('keyup', '#employee_id', function(event) {
            if (event.keyCode === 13) {
                $('.create_user').click();
            }
        });
       
        $(document).on('keyup', '#password_id', function(event) {
            if (event.keyCode === 13) {
                $('.create_user').click();
            }
        });

        $(document).on('keyup', '#password_confirmation_id', function(event) {
            if (event.keyCode === 13) {
                $('.create_user').click();
            }
        });
        

        $(document).on('keyup', '#monthly_salary_id', function(event) {
            if (event.keyCode === 13) {
                $('.create_user').click();
            }
        });

        $(document).on('keyup', '#phone_id', function(event) {
            if (event.keyCode === 13) {
                $('.create_user').click();
            }
        });



        $(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                $('#add_employee').modal('hide');
                $('#edit_employee').modal('hide');
                $('#delete_employee').modal('hide');

            }
        });

        $('.create-modal').click(function(e) {
            $('.datetimepicker').datepicker('setDate', new Date());
        });

        /**
         * Add user form - submit button action
         */
        $(document).on('click', '.create-user', function(e) {
            $('.field-error').html('');
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var data = new FormData($('#add_employee_form')[0]);
            $.ajax({
                type: 'POST',
                url:  $('#add_employee_form').attr('action'),
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $('.non-client-field').show();
                    $(".overlay").remove();
                    $('#add_employee').modal('hide');
                    toastr.success(response.message, 'Added');
                    getUsersGrid('');
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

        $(document).on('click','.add-user',function(){
            $.get('get-create-form',
            function(response) {
                $("#employee_id").val(response.newEmployeeCode);
            }, 'json');
        });

        /**
         * user create form reset
         */
        $(document).on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_employee_form').trigger('reset');
            inputsLoader();
            $('.chosen-select').val('').trigger('chosen:updated');
        });

          /** to  update on enter key */
            $(document).on('keyup', '#first_name', function(event) {
            if (event.keyCode === 13) {
                $('.update-user').click();
            }
        });
        
        $(document).on('keyup', '#last_name', function(event) {
            if (event.keyCode === 13) {
                $('.update-user').click();
            }
        });
        
        $(document).on('keyup', '#email', function(event) {
            if (event.keyCode === 13) {
                $('.update-user').click();
            }
        });
       
        $(document).on('keyup', '#employee_id', function(event) {
            if (event.keyCode === 13) {
                $('.update-user').click();
            }
        });
        
        $(document).on('keyup', '#password', function(event) {
            if (event.keyCode === 13) {
                $('.update-user').click();
            }
        });

        $(document).on('keyup', '#epassword_confirmation', function(event) {
            if (event.keyCode === 13) {
                $('.update-user').click();
            }
        });
        
        $(document).on('keyup', '#phone', function(event) {
            if (event.keyCode === 13) {
                $('.update-user').click();
            }
        });

        $(document).on('keyup', '#monthly_salary', function(event) {
            if (event.keyCode === 13) {
                $('.update-user').click();
            }
        });


        /**
         * user edit form loading when clicking edit
         */
        $(document).on('click', '.edit-user', function() {
            $('.user-success').addClass('hidden');
            var userId = $(this).data('id');
            var editUrl = '/users/' + userId + '/edit';
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $(".overlay").remove();
                    $("#edit_employee").modal('show');
                    $('#edit_employee').html(data);
                    typeAhead();
                    inputsLoader();
                },
                error: function(error) {
                    $(".overlay").remove();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#edit_label_' + field).html(error);
                        });
                    }
                }
            });

        });

        /**
         * Edit user form - submit button action
         */
        $(document).on('click', '.update-user', function(e) {
            $('.user-success').addClass('hidden');
            $('.field-error').html('');
            e.preventDefault();
            var data = new FormData($('#edit_employee_form')[0]);
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                type: 'POST',
                url: $('#edit_employee_form').attr('action'),
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $(".overlay").remove();
                    $('#edit_employee').modal('hide');
                    toastr.success(response.message, 'Updated');
                    if(response.img != '') {
                        $("#header_pic").attr('src', response.img);
                    }
                    getUsersGrid('');
                },
                error: function(error) {
                    $(".overlay").remove();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#edit_label_' + field).html(error);
                        });
                    }
                }
            });
        });

        /** 
         * Loading view employee form with data to view modal
         */
        $(document).on('click', '.view-employee', function() {
            var userId = $(this).data('id');
            showUrl = '/users/' + userId;
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                method: 'GET',
                url: showUrl,
                data: {},
                success: function(response) {
                    $('#view_employee').html(response);
                    $(".overlay").remove();
                },
                error: function(error) {
                    // console.log(error);
                    $(".overlay").remove();
                }
            });
        });

        $(document).on('click', '.delete-user', function() {
            var deleteUserId = $(this).data('id');
            $('#delete_employee #delete_user_id').val(deleteUserId);
        });

        /**
         * Delete user - button click action
         */
        $(document).on('click', '#delete_employee .continue-btn', function() {
            var deleteUserId = $('#delete_employee #delete_user_id').val();
            deleteUrl = '/users/' + deleteUserId;
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#delete_employee').modal('hide');
            $.ajax({
                type: 'DELETE',
                url: deleteUrl,
                data: {},
                success: function(response) {
                    $(".overlay").remove();
                    toastr.success(response.message, 'Deleted');
                    getUsersGrid('');
                },
                error: function(error) {
                    $(".overlay").remove();
                    $('#delete_employee').modal('hide');
                    toastr.warning("Delete the user involved items before you delete user", 'Warning');
                }
            });
        });

        /**
         * Search user - button click action
         */
        var timer;
      /*   jQuery(document).on('change', '.search-user', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                if ($('.search-user').val().length < 4 && $('.search-user').val().length >0) return;
                getUsersGrid('employee_name',$('.search-user').val());
            },300);
        }); */

        jQuery(document).on('change', '.search-user', function() {
            getUsersGrid('employee_name',$('.search-user').val());
        });
        
        jQuery(document).on('click', '.search-button', function() {
            getUsersGrid('employee_name',$('.search-user').val());
        });

        jQuery(document).on('change', '#user-role', function() {
            getUsersGrid('role',$('#user-role').val());
        });

        jQuery(document).on('change', '#user-type', function() {
            getUsersGrid('employee_type',$('#user-type').val());
        });


        /**
        * Hide non client fields on user Role type change
        **/
        jQuery(document).on('change', '#role_id', function() {
            var roleId = jQuery(this).val();
            if(roleId == 4) {
                jQuery('.non-client-field').hide();
            } else {
                jQuery('.non-client-field').show();
                if($("#edit_employee_id").val() == '') {
                    $.get('get-create-form',
                    function(response) {
                        $("#edit_employee_id").val(response.newEmployeeCode);
                    }, 'json');
                    
                }
            }   
        });
    });

    /** 
     * function for displaying user grid
     */
    function getUsersGrid(type, val) {
        $.ajax({
            type: 'POST',
            url: '/get-users-grid',
            data: {
                'filter_type' : type,
                'filter_value': val
            },
            success: function(response) {
                $('.grid').html(response.data);
                typeAhead();
                inputsLoader();
                
                if(type == 'employee_name') {
                    $('.search-user').focus().val(val);
                } else if (type == 'role') {
                    $("#user-role > [value=" + val + "]").attr("selected", "true");
                } else {
                    $("#user-type > [value=" + val + "]").attr("selected", "true");
                }
            }
        });
    }

    function view(id){
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $.ajax({
            type: 'POST',
            url: '/get-single-user',
            data: {
                'employee_Id': id
            },
            success: function(response) {
                $('.viewDetails').html(response.data);
                $(".overlay").remove();
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }
        });
    }

    function typeAhead(){
        $.get('get-typhead-data-user',
            function(response) {
                var name = [],designations =[],departments =[];
                for (var i = response.data.length - 1; i >= 0; i--) {
                    name.push(response.data[i]['first_name']);
                }
                for (var i = response.designations.length - 1; i >= 0; i--) {
                    designations.push(response.designations[i]['name']);
                }
                for (var i = response.departments.length - 1; i >= 0; i--) {
                    departments.push(response.departments[i]['name']);
                }  
                $(".typeahead_name").typeahead({
                    source: name
                });
                $(".typeahead_designation").typeahead({
                    source: designations
                });
                $(".typeahead_department").typeahead({
                    source: departments
                });
            }, 'json');
    }
    function inputsLoader() {
        
        $('.datetimepicker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true
        });


        $('.chosen-select').chosen({
            width: "100%"
        });
        $(".tab-pane").css({
            height: "100%"
        });
    }
