inputsLoader();
	function inputsLoader(){
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

		$('.chosen-select').chosen({
			width:'100%'
		});

		$('.pre-table').dataTable();

		$('.cur-table').dataTable();

        $('.summernote').summernote();
	}

	/* Adding application id to hidden text field in accept model 
         */
        $(document).on('click', '.accept-application', function() {
            var  acceptApplicationId = $(this).data('id');
            $('#accept_application #accept_application_id').val( acceptApplicationId);
        });

        /**
         * accept model continue button action
         */
        $(document).on('click', '#accept_application .continue-btn', function() {
            var acceptApplicationId = $('#accept_application #accept_application_id').val();
            openLoader();
            $('#accept_application').modal('hide');
            $.ajax({
                method: 'post',
                url: '/accept-application-admin',
                data: {'ApplicationId':acceptApplicationId},
                success: function(res) {
                    $('.main').html(res.data);
                    inputsLoader();
                    closeLoader();
                    $('#accept_application').trigger('reset');
                    toastr.success(res.message,'Approved');
                    
                }
            });
        });

        /* Adding application id to hidden text field in reject model 
         */
        $(document).on('click', '.reject-application', function() {
            var  rejectApplicationId = $(this).data('id');
            $('#reject_reason #reject_application_id').val( rejectApplicationId);
        });

        /**
         * reject model continue button action
         */
        $(document).on('click', '#reject_reason .continue-btn', function() {
            var rejectApplicationId = $('#reject_reason #reject_application_id').val();
            openLoader();
            $.ajax({
                method: 'post',
                url: '/reject-application-admin',
                data: $('#application_rejection_form').serialize(),
                success: function(res) {
                    $('#reject_reason').modal('hide');
                    $('#reject_reason').trigger('reset');
                    $('.main').html(res.data);
                    inputsLoader();
                    closeLoader();
                    toastr.success(res.message,'Rejected');
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

         $(document).on('change', '.year-search, #user', function(e) {
	        $('.field-error').html('');
	        e.preventDefault();
	        openLoader();
	        $.ajax({
	            type: 'POST',
	            url: '/application-search',
	            data: {
	            	'date':$('.year-search').val(),
	            	'user_id':$('#user').val()
	            },
	            success: function(response) {
	            	$('.previous-app').html(response.data);
	            	inputsLoader();
	                closeLoader();
	            },
	            error: function(error) {
	            	closeLoader();
	            	toastr.error('Something went wrong', 'Error');
	            }
	        });
	    });

    $(document).on('click', '.delete-application', function() {
        var deleteApplicationId = $(this).data('id');
        $('#delete_application #delete_application_id').val(deleteApplicationId);
    });

    /**
     * Delete model continue button action
     */
    $(document).on('click', '#delete_application .continue-btn', function() {
        var deleteApplicationId = $('#delete_application #delete_application_id').val();
        openLoader();
        $('#delete_application').modal('hide');
        $.ajax({
            method: 'DELETE',
            url: '/compensations/' + deleteApplicationId,
            data: {},
            success: function(response) {
                toastr.success('Deleted Successfully', 'Deleted');
                $('.year-search').trigger('change');
            },
            error: function(error) {
                closeLoader();
                toastr.error('Something went wrong', 'Error');
            }
        });
    });

        $(document).on('click', '.edit-application', function() {
        var applicationId = $(this).data('id');
        editUrl = '/compensations/' + applicationId + '/edit';
        openLoader();
        $.ajax({
            method: 'GET',
            url: editUrl,
            data: {},
            success: function(response) {
                $('#edit_application').html(response);
                $('#edit_application').modal('show');
                inputsLoader();
                closeLoader();

            }
        });
    });

    $(document).on('click', '.update-details', function(e) {
        $('.field-error').html('');
        e.preventDefault();
        openLoader();
        $.ajax({
            type: 'POST',
            url: $('#edit_form').attr('action'),
            data: $('#edit_form').serialize(),
            success: function(response) {
                $('#edit_application').modal('hide');
                toastr.success('Updated Application Details', 'Updated');
                $('.year-search').trigger('change');
            },
            error: function(error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_edit_' + field).html(error);
                    });
                }
            }
        });
    });