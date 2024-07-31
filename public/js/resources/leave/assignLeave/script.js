
    function inputsLoader(){
    //select box 
        $('.chosen-select').chosen({
            width: "100%"
        });
            //date picker
            $('#fromdate').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#todate').datepicker('setStartDate', minDate);
        });
        $('#todate').datepicker({

            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true
        }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('#fromdate').datepicker('setEndDate', maxDate);
        });
        $('.summernote').summernote();
    }
    inputsLoader();

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


    //to serach based on year
    $(document).on("change",'#user', function () {
        if($(this).val() == '') {
            $("#leave_list").hide();
            $("#apply_leave_link").hide();
        } else {
            reload(); 
        }
                   
    });


    $(document).keydown(function(e) {
        // enter key pressed
        if (e.keyCode == 13) {
            $('.apply-leave').click();

        }
    });

    /** 
     * Create leave form - submit buttom action
     */
    $(document).on('click','.apply-leave',function(e) {
        $('.field-error').html('');
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $('#apply_leave_form').attr('action'),
            data: $('#apply_leave_form').serialize(),
            success: function(res) {
                $(".overlay").remove();
                $('#apply_leave').modal('hide');
                $('#apply_leave_form').trigger('reset');
                if(res.flag)
                    toastr.success(res.message, "Applied");
                else
                toastr.warning(res.message, "Warning");
                reload();
            },
            error: function(error) {
                $(".overlay").remove();
                if (error.responseJSON.errors) {
                    console.log(error.responseJSON.errors);
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
    $('#apply_leave').on('hidden.bs.modal', function() {
        
        $(this).find('.text-danger').html('');
        $('#apply_leave_form').trigger('reset');
        $('.chosen-select').val('').trigger('chosen:updated');
    });

    function reload() {
        openLoader();
        $.ajax({
            type: 'POST',
            url: '/get-user-leave-applications',
            data: {
                'user_id' : $("#user").val()
            },
            success: function(response) {
                closeLoader();
                $("#leave_list").show();
                $("#apply_leave_link").show();
                $('#leave_applications').html(response.data);
                inputsLoader();
                $("#user_id").val($("#user").val());
            }
        });

    }
