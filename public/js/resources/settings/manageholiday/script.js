
            //year picker
            $('#data_3 .input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                defaultDate: new Date()
            });

            $('#data_3 .input-group.date').datepicker('setDate', new Date());
            //date picker
            $('.datetimepicker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true
        });

        reload();

            //save on enter key
            
        $(document).on('keyup', '#holiday_name_id', function(event) {
            // enter key pressed
            if (event.keyCode === 13) {
                $('.create-holiday').click();

            }
        });

        //update on enter key
        $(document).on('keyup', '#edit_holiday_name', function(event) {
            // enter key pressed
            if (event.keyCode === 13) {
                $('.update-holiday').click();

            }
        });

        /** 
         * Create overhead form - submit buttom action
         */
        $('.create-holiday').click(function(e) {
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var data = $('#add_holiday_form').serialize();
            
            $.ajax({
                type: 'POST',
                url: $('#add_holiday_form').attr('action'),
                data: data,
                success: function(res) {
                    $(".overlay").remove();
                    $('#add_holiday').modal('hide');
                    $('#add_holiday_form').trigger('reset');
                    toastr.success(res.message,'Created');
                    reload();
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
        $('#weekly_holiday').on('hidden.bs.modal', function() {
            Swal.fire({
              title: 'Are you sure?',
              text: "You can edit it later if you want!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, apply changes!'
            }).then((result) => {
                if (result.value) {
                      var list =[];
                        $('#Sunday').prop("checked") ? list.push($('#Sunday').val()) : '';
                        $('#Monday').prop("checked") ? list.push($('#Monday').val()) : '';
                        $('#Tuesday').prop("checked") ? list.push($('#Tuesday').val()) : '';
                        $('#Wednesday').prop("checked") ? list.push($('#Wednesday').val()) : '';
                        $('#Thursday').prop("checked") ? list.push($('#Thursday').val()) : '';
                        $('#Friday').prop("checked") ? list.push($('#Friday').val()) : '';
                        $('#Saturday').prop("checked") ? list.push($('#Saturday').val()) : '';
                        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
                         $.ajax({
                            type: 'POST',
                            url: '/manage-weeklyholidays',
                            data: {
                                'days':list
                            },
                            success: function(res) {
                                 $(".overlay").remove();
                                toastr.success('','Saving');
                            },
                            error: function(error) {
                                 $(".overlay").remove();
                                console.log(error);
                            }
                        });
                     }
            });
            
        });


        /**
         * Removing validation errors and reset form on model window close
         */
        $('#add_holiday').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_holiday_form').trigger('reset');
        });

        /** 
         * Loading edit overhead form with data to edit modal
         */
        $(document).on('click', '.edit-holiday', function() {
            var holidayId = $(this).data('id');
            editUrl = '/manage-holidays/' + holidayId + '/edit';
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                method: 'GET',
                url: editUrl,
                data: {},
                success: function(response) {
                    $(".overlay").remove();
                    $('#edit_holidays').html(response);
                    $('#edit_holidays').modal('show');
                }
            });
        });

        /**
         * Update holiday form - submit button action
         */
        $(document).on('click', '.update-holiday', function(e) {
            $('.field-error').html('');
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                type: 'PATCH',
                url: $('#edit_holiday_form').attr('action'),
                data: $('#edit_holiday_form').serialize(),
                success: function(res) {
                    $(".overlay").remove();
                    $('#edit_holidays').modal('hide');
                    toastr.success(res.message,'Updated');
                    reload();
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
         * Adding holiday id to hidden text field in delete model 
         */
        $(document).on('click', '.delete-holiday', function() {
            var deleteHolidayId = $(this).data('id');
            $('#delete_holiday #delete_holiday_id').val(deleteHolidayId);
        });

        /**
         * Delete model continue button action
         */
        $(document).on('click', '#delete_holiday .continue-btn', function() {
            var deleteHolidayId = $('#delete_holiday #delete_holiday_id').val();
            var deleteUrl = '/manage-holidays/' + deleteHolidayId;
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#delete_holiday').modal('hide');
            $.ajax({
                method: 'DELETE',
                url: deleteUrl,
                data: {},
                success: function(res) {
                    $(".overlay").remove();
                    $('#delete_holiday').trigger('reset');
                    toastr.success(res.message,'Deleted');
                    reload();
                }
            });
        });



        function reload() {

            $.ajax({
                type:'POST',
                url:'/get-holiday-list',
                data:{
                    'holiday_date':$('#date-chart').val()
                },
                success: function( response ) {
                    $('#holiday_container').html(response.data);
                    $('#holiday_list').dataTable({
                        "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
                    });
                }
            });
            
        }

        //to serach based on year
        $(document).on("change",'#date-chart', function () {
            reload();            
        });
