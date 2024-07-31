inputsloader();

	 $(document).on('click', '.create-recruitment', function(e) {
        $('.text-danger').html('');
        e.preventDefault();
        openLoader();
        $.ajax({
            type: 'POST',
            url: $('#add_form').attr('action'),
            data: new FormData($('#add_form')[0]),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
            	$('#create_candidate').modal('hide');
            	$('#list').html(response.data);
                $('#add_form').find('.chosen-select').val('').trigger('chosen:updated');
                $('#status').val('Pending').trigger('chosen:updated');
                $('.collapse-link').addClass('new-collapse');
                inputsloader();
                closeLoader();
                toastr.success('Candidate Added Successfully', 'Saved');
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

	 $('#create_candidate').on('hidden.bs.modal', function() {
        $(this).find('.text-danger').html('');
        $('#add_form').trigger('reset');        
        $('.summernote').summernote('reset');
    });

	function inputsloader() {

		$('.footable').footable();

		$('.datetimepicker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true
        });

        $('.clockpicker').clockpicker();

		$('.chosen-select').chosen({
			width:'100%'
		});
		$('.summernote').summernote({
			height:100
		});

		 $('#daterange').daterangepicker({
                    opens: 'left',
                      locale: {
                        format: 'MMM DD, YYYY'
                      },
                    maxDate: moment(),
                    ranges: {
                       'Today': [moment(), moment()],
                       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                       'This Month': [moment().startOf('month'), moment().endOf('month')],
                       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);
        }
	 function cb(start, end) {
        $('#daterange').val(start.format('MMM DD, YYYY') + ' - ' + end.format('MMM DD, YYYY'));
        search();
    }

    $(document).on('change', '#category', function(e) {
        search();
    });

    $(document).on('change', '#status', function(e) {
        search();
    });

    $(document).on('change', '#name', function(e) {
        search();
    });

     function search(){
        openLoader();
        $.ajax({
            type: 'POST',
            url: '/search-candidate',
            data: {
            	'category':$('#category').val(),
            	'status':$('#status').val(),
            	'name':$('#name').val(),
            	'date':$('#daterange').val()
            },
            success: function(response) {
                closeLoader();
                $('.candidates').html(response.data);
                inputsloader();
            },
            error: function(error) {
            }
        });
    }

     $(document).on('click', '.reset', function(e) {
        e.preventDefault();
        openLoader();
        $('#category').val('').trigger('chosen:updated'),
    	$('#status').val('').trigger('chosen:updated'),
    	$('#name').val('').trigger('chosen:updated'),
        $('#daterange').val('')
        search();
       	closeLoader();
    });

     $(document).on('change', '#schedule_search', function(e) {
        e.preventDefault();
        openLoader();
        $.ajax({
            type: 'POST',
            url: '/search-schedule',
            data: {
            	'date':$('.datetimepicker').val()
            },
            success: function(response) {
                closeLoader();
                $('.schedule').html(response.data);
                inputsloader();
                $('.collapse-link').addClass('new-collapse');
            },
            error: function(error) {
            }
        });
    });

$(document).on('click', '.delete-candidate', function() {
        var deletecandidateId = $(this).data('id');
        $('#delete_candidate #delete_candidate_id').val(deletecandidateId);
        $('#delete_candidate').modal('show');
    });

    /**
     * Delete model continue button action
     */
    $(document).on('click', '#delete_candidate .continue-btn', function() {
        var deletecandidateId = $('#delete_candidate #delete_candidate_id').val();
        openLoader();
        $('#delete_candidate').modal('hide');
        $.ajax({
            method: 'DELETE',
            url: '/recruitments/' + deletecandidateId,
            data: {},
            success: function(response) {
                $('#list').html(response.data);
                $('.collapse-link').addClass('new-collapse');
                inputsloader();
                closeLoader();
                toastr.success('Candidate Deleted Successfully', 'Deleted');
            },
            error: function(error) {
                closeLoader();
                toastr.error('Something went wrong', 'Error');
            }
        });
    });

    $(document).on('click', '.new-schedule', function() {
        var candidateId = $(this).attr('data-id');
        openLoader();
         $.ajax({
                type: 'POST',
                url: '/get-schedule',
                data: {
                	'id':candidateId
                },
                success: function(data) {
                    $("#new_schedule").modal('show');
                    $('#new_schedule').html(data.data);
                    $('.collapse-link').addClass('new-collapse');
                    $('.chosen-select').chosen({
						width:'100%'
					});

                    $('.clockpicker').datetimepicker({
                        format: "hh:mm A",
                    });
					$('.datetimepicker-new').datepicker({
			            todayBtn: "linked",
			            keyboardNavigation: false,
			            forceParse: false,
			            format: "dd/mm/yyyy",
			            autoclose: true,
			        });
                    closeLoader();
                },
                error: function(error) {
	                closeLoader();
	                toastr.error('Something went wrong', 'Error');
	            }
            });
    });

     $(document).on('click', '#new_schedule .continue-btn', function(e) {
        e.preventDefault();
        openLoader();
        $.ajax({
            type: 'POST',
            url: $('#update_schedule').attr('action'),
            data: $('#update_schedule').serialize(),
            success: function(response) {
            	$('#new_schedule').modal('hide');
            	$('#list').html(response.data);
                $('.collapse-link').addClass('new-collapse');
                inputsloader();
                closeLoader();
                toastr.success('Schedule Updated Successfully', 'Updated');
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

     $(document).on('click', '.edit-candidate', function() {
            $('.candidate-success').addClass('hidden');
            var candidateId = $(this).data('id');
            var editUrl = '/recruitments/' + candidateId + '/edit';
            openLoader();
            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    closeLoader();
                    $("#edit_candidate").modal('show');
                    $('#edit_candidate').html(data);
                    inputsloader();
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

        $(document).on('click', '.update-candidate', function(e) {
            $('.field-error').html('');
            e.preventDefault();
            var data = new FormData($('#edit_form')[0]);
            openLoader();
            $.ajax({
                type: 'POST',
                url: $('#edit_form').attr('action'),
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    closeLoader();
                    $('#edit_candidate').modal('hide');
                    $('#list').html(response.data);
                    $('.collapse-link').addClass('new-collapse');
                    toastr.success('Candidate details updated Successfully', 'Updated');
                    inputsloader();
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

    $(document).on('click', '.new-collapse',  function(e){
        e.preventDefault();
	  var ibox = $(this).closest('.ibox');
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